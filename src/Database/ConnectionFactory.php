<?php

declare(strict_types=1);

namespace App\Database;

use PDO;

final class ConnectionFactory
{
    public static function create(string $host, int $port, string $database, string $user, string $password): PDO
    {
        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $host, $port, $database);

        return new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Real server-side prepared statements, not client-side emulation.
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
}
