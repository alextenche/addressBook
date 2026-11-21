<?php

declare(strict_types=1);

namespace App\Validation;

use App\Domain\ContactGroup;
use App\Domain\ContactInput;

final class ContactValidator
{
    /** Field => [label, max length, required]. Lengths match the database columns. */
    private const array FIELDS = [
        'first_name' => ['First name', 100, true],
        'last_name' => ['Last name', 100, true],
        'email' => ['Email', 254, false],
        'phone' => ['Phone', 50, false],
        'address_line1' => ['Address', 150, false],
        'address_line2' => ['Address line 2', 150, false],
        'city' => ['City', 100, false],
        'region' => ['Region', 100, false],
        'postal_code' => ['Postal code', 20, false],
        'contact_group' => ['Group', 20, true],
        'notes' => ['Notes', 2000, false],
    ];

    /**
     * @param array<mixed> $raw Untrusted input, e.g. a parsed request body.
     *
     * @throws ValidationException
     */
    public function validate(array $raw): ContactInput
    {
        /** @var array<string, string> $values */
        $values = [];
        /** @var array<string, string> $errors */
        $errors = [];

        foreach (self::FIELDS as $field => [$label, $max, $required]) {
            $value = $raw[$field] ?? null;
            if ($value !== null && !is_scalar($value)) {
                $errors[$field] = "{$label} must be text.";
                $values[$field] = '';
                continue;
            }
            // Normalise: trim, unify line endings, strip control characters other than newlines and tabs.
            $value = trim(str_replace("\r\n", "\n", (string) $value));
            $value = (string) preg_replace('/[^\P{C}\n\t]/u', '', $value);
            $values[$field] = $value;

            if ($value === '') {
                if ($required) {
                    $errors[$field] = "{$label} is required.";
                }
                continue;
            }
            if (mb_strlen($value) > $max) {
                $errors[$field] = "{$label} must be at most {$max} characters.";
            }
        }

        if (!isset($errors['email']) && $values['email'] !== '' && filter_var($values['email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Email must be a valid email address.';
        }
        if (!isset($errors['phone']) && $values['phone'] !== '' && preg_match('/^\+?[0-9 ().\-\/]{3,50}$/', $values['phone']) !== 1) {
            $errors['phone'] = 'Phone may only contain digits, spaces and + ( ) - . /';
        }
        $group = ContactGroup::tryFrom($values['contact_group']);
        if (!isset($errors['contact_group']) && $group === null) {
            $errors['contact_group'] = 'Group must be one of: ' . implode(', ', ContactGroup::values()) . '.';
        }

        if ($errors !== [] || $group === null) {
            throw new ValidationException($errors, $values);
        }

        $optional = static fn(string $field): ?string => $values[$field] === '' ? null : $values[$field];

        return new ContactInput(
            firstName: $values['first_name'],
            lastName: $values['last_name'],
            group: $group,
            email: $optional('email'),
            phone: $optional('phone'),
            addressLine1: $optional('address_line1'),
            addressLine2: $optional('address_line2'),
            city: $optional('city'),
            region: $optional('region'),
            postalCode: $optional('postal_code'),
            notes: $optional('notes'),
        );
    }
}
