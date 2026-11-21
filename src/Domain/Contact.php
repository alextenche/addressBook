<?php

declare(strict_types=1);

namespace App\Domain;

use DateTimeImmutable;

final readonly class Contact
{
    public function __construct(
        public int $id,
        public string $firstName,
        public string $lastName,
        public ContactGroup $group,
        public ?string $email,
        public ?string $phone,
        public ?string $addressLine1,
        public ?string $addressLine2,
        public ?string $city,
        public ?string $region,
        public ?string $postalCode,
        public ?string $notes,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt,
    ) {}

    /** @param array<string, mixed> $row A row from the contacts table. */
    public static function fromRow(array $row): self
    {
        $nullable = static fn(string $key): ?string => isset($row[$key]) && $row[$key] !== ''
            ? (string) $row[$key]
            : null;

        return new self(
            id: (int) $row['id'],
            firstName: (string) $row['first_name'],
            lastName: (string) $row['last_name'],
            group: ContactGroup::from((string) $row['contact_group']),
            email: $nullable('email'),
            phone: $nullable('phone'),
            addressLine1: $nullable('address_line1'),
            addressLine2: $nullable('address_line2'),
            city: $nullable('city'),
            region: $nullable('region'),
            postalCode: $nullable('postal_code'),
            notes: $nullable('notes'),
            createdAt: new DateTimeImmutable((string) $row['created_at']),
            updatedAt: new DateTimeImmutable((string) $row['updated_at']),
        );
    }

    public function fullName(): string
    {
        return trim($this->firstName . ' ' . $this->lastName);
    }

    public function initials(): string
    {
        return mb_strtoupper(mb_substr($this->firstName, 0, 1) . mb_substr($this->lastName, 0, 1));
    }

    /** The city/region/postcode line, e.g. "Timisoara, Timis 300200". */
    public function locality(): ?string
    {
        $place = implode(', ', array_filter([$this->city, $this->region], static fn(?string $v): bool => $v !== null));
        $line = trim($place . ' ' . ($this->postalCode ?? ''));

        return $line === '' ? null : $line;
    }

    /** @return array<string, int|string|null> */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
            'address_line1' => $this->addressLine1,
            'address_line2' => $this->addressLine2,
            'city' => $this->city,
            'region' => $this->region,
            'postal_code' => $this->postalCode,
            'contact_group' => $this->group->value,
            'notes' => $this->notes,
            'created_at' => $this->createdAt->format(DATE_ATOM),
            'updated_at' => $this->updatedAt->format(DATE_ATOM),
        ];
    }
}
