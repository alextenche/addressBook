<?php

declare(strict_types=1);

namespace App\Database;

use PDO;
use PDOStatement;
use RuntimeException;

/**
 * A deliberately tiny migration runner: applies database/migrations/*.sql in
 * filename order and records each one in a `migrations` table.
 */
final class Migrator
{
    public function __construct(
        private readonly PDO $pdo,
        private readonly string $migrationsDir,
        private readonly string $seedFile,
    ) {}

    /** @return list<string> The migrations that were applied by this call. */
    public function migrate(): array
    {
        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS migrations (
                name VARCHAR(255) NOT NULL PRIMARY KEY,
                applied_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci',
        );

        $applied = $this->query('SELECT name FROM migrations')->fetchAll(PDO::FETCH_COLUMN);
        $files = glob($this->migrationsDir . '/*.sql') ?: [];
        sort($files);

        $ran = [];
        foreach ($files as $file) {
            $name = basename($file);
            if (in_array($name, $applied, true)) {
                continue;
            }
            // MySQL DDL auto-commits, so a transaction would not help here; one statement per file keeps it simple.
            $this->pdo->exec($this->read($file));
            $this->pdo->prepare('INSERT INTO migrations (name) VALUES (:name)')->execute(['name' => $name]);
            $ran[] = $name;
        }

        return $ran;
    }

    /** Loads the demo contacts, but only into an empty table. Returns whether it seeded. */
    public function seed(): bool
    {
        $count = (int) $this->query('SELECT COUNT(*) FROM contacts')->fetchColumn();
        if ($count > 0) {
            return false;
        }
        $this->pdo->exec($this->read($this->seedFile));

        return true;
    }

    /** Drops every table in the current database. Used by the test suite. */
    public function dropAll(): void
    {
        $tables = $this->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
        foreach ($tables as $table) {
            $this->pdo->exec('DROP TABLE IF EXISTS `' . str_replace('`', '``', (string) $table) . '`');
        }
    }

    private function query(string $sql): PDOStatement
    {
        // With ERRMODE_EXCEPTION, query() throws rather than returning false.
        return $this->pdo->query($sql) ?: throw new RuntimeException("Query failed: {$sql}");
    }

    private function read(string $file): string
    {
        $sql = file_get_contents($file);
        if ($sql === false) {
            throw new RuntimeException("Cannot read {$file}");
        }

        return $sql;
    }
}
