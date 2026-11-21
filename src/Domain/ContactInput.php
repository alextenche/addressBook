<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Validated, normalised data for creating or updating a contact.
 * Only ContactValidator builds these, so every instance is known to be valid.
 */
final readonly class ContactInput
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public ContactGroup $group,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $addressLine1 = null,
        public ?string $addressLine2 = null,
        public ?string $city = null,
        public ?string $region = null,
        public ?string $postalCode = null,
        public ?string $notes = null,
    ) {}

    /** @return array<string, string|null> Column => value, ready for a prepared statement. */
    public function toColumns(): array
    {
        return [
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
        ];
    }
}
