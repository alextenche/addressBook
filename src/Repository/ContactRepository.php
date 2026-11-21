<?php

declare(strict_types=1);

namespace App\Repository;

use App\Domain\Contact;
use App\Domain\ContactGroup;
use App\Domain\ContactInput;
use PDO;
use RuntimeException;

final class ContactRepository
{
    public function __construct(private readonly PDO $pdo) {}

    /** @return list<Contact> */
    public function search(?string $query = null, ?ContactGroup $group = null): array
    {
        $where = [];
        $params = [];

        $query = $query === null ? '' : trim($query);
        if ($query !== '') {
            // Escape LIKE wildcards so a search for "50%" means the literal text.
            $like = '%' . addcslashes($query, '%_\\') . '%';
            $where[] = "(CONCAT(first_name, ' ', last_name) LIKE :q1 OR email LIKE :q2 OR phone LIKE :q3 OR city LIKE :q4)";
            $params += ['q1' => $like, 'q2' => $like, 'q3' => $like, 'q4' => $like];
        }
        if ($group !== null) {
            $where[] = 'contact_group = :grp';
            $params['grp'] = $group->value;
        }

        $sql = 'SELECT * FROM contacts'
            . ($where === [] ? '' : ' WHERE ' . implode(' AND ', $where))
            . ' ORDER BY last_name, first_name, id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        /** @var list<array<string, mixed>> $rows */
        $rows = $stmt->fetchAll();

        return array_map(Contact::fromRow(...), $rows);
    }

    public function find(int $id): ?Contact
    {
        $stmt = $this->pdo->prepare('SELECT * FROM contacts WHERE id = :id');
        $stmt->execute(['id' => $id]);

        /** @var array<string, mixed>|false $row */
        $row = $stmt->fetch();

        return $row === false ? null : Contact::fromRow($row);
    }

    public function create(ContactInput $input): Contact
    {
        $columns = $input->toColumns();
        $names = array_keys($columns);

        $sql = sprintf(
            'INSERT INTO contacts (%s) VALUES (%s)',
            implode(', ', $names),
            implode(', ', array_map(static fn(string $c): string => ':' . $c, $names)),
        );
        $this->pdo->prepare($sql)->execute($columns);

        return $this->find((int) $this->pdo->lastInsertId())
            ?? throw new RuntimeException('Contact vanished right after insert.');
    }

    public function update(int $id, ContactInput $input): ?Contact
    {
        if ($this->find($id) === null) {
            return null;
        }

        $columns = $input->toColumns();
        $assignments = array_map(static fn(string $c): string => "{$c} = :{$c}", array_keys($columns));

        $this->pdo
            ->prepare('UPDATE contacts SET ' . implode(', ', $assignments) . ' WHERE id = :id')
            ->execute($columns + ['id' => $id]);

        return $this->find($id);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM contacts WHERE id = :id');
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;
    }
}
