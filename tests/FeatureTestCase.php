<?php

declare(strict_types=1);

namespace Tests;

use App\Database\Migrator;
use App\Domain\Contact;
use App\Repository\ContactRepository;
use App\Validation\ContactValidator;
use PDO;

/** Runs against a real MariaDB/MySQL database (addressbook_test), emptied before each test. */
abstract class FeatureTestCase extends TestCase
{
    private static bool $migrated = false;

    protected function setUp(): void
    {
        parent::setUp();

        if (!self::$migrated) {
            $migrator = $this->container()->get(Migrator::class);
            $migrator->dropAll();
            $migrator->migrate();
            self::$migrated = true;
        }
        $this->container()->get(PDO::class)->exec('DELETE FROM contacts');
    }

    /** @param array<string, string> $overrides */
    protected function createContact(array $overrides = []): Contact
    {
        $input = (new ContactValidator())->validate($overrides + [
            'first_name' => 'Ada',
            'last_name' => 'Lovelace',
            'email' => 'ada@example.com',
            'contact_group' => 'business',
        ]);

        return $this->container()->get(ContactRepository::class)->create($input);
    }

    /** @return array<string, string> A valid request payload. */
    protected function validPayload(): array
    {
        return [
            'first_name' => 'Grace',
            'last_name' => 'Hopper',
            'email' => 'grace@example.com',
            'phone' => '+1 555 0100',
            'city' => 'Arlington',
            'contact_group' => 'business',
        ];
    }
}
