<?php

declare(strict_types=1);

use App\Domain\Contact;
use App\Domain\ContactGroup;

function contactRow(array $overrides = []): array
{
    return $overrides + [
        'id' => '7', 'first_name' => 'émile', 'last_name' => 'Zola', 'email' => 'emile@example.com',
        'phone' => null, 'address_line1' => '21 Rue de Bruxelles', 'address_line2' => '',
        'city' => 'Paris', 'region' => null, 'postal_code' => '75009', 'contact_group' => 'friends',
        'notes' => null, 'created_at' => '2026-01-02 03:04:05', 'updated_at' => '2026-01-02 03:04:05',
    ];
}

it('hydrates from a database row', function () {
    $contact = Contact::fromRow(contactRow());

    expect($contact->id)->toBe(7)
        ->and($contact->group)->toBe(ContactGroup::Friends)
        ->and($contact->addressLine2)->toBeNull()
        ->and($contact->fullName())->toBe('émile Zola')
        ->and($contact->initials())->toBe('ÉZ');
});

it('builds a locality line from whatever parts exist', function (array $parts, ?string $expected) {
    expect(Contact::fromRow(contactRow($parts))->locality())->toBe($expected);
})->with([
    'all parts' => [['city' => 'Timisoara', 'region' => 'Timis', 'postal_code' => '300200'], 'Timisoara, Timis 300200'],
    'city and postcode' => [[], 'Paris 75009'],
    'nothing' => [['city' => null, 'postal_code' => null], null],
]);

it('serialises to the API shape', function () {
    expect(Contact::fromRow(contactRow())->toArray())
        ->toHaveKeys(['id', 'first_name', 'contact_group', 'created_at'])
        ->and(Contact::fromRow(contactRow())->toArray()['contact_group'])->toBe('friends');
});

it('lists group values for validation messages', function () {
    expect(ContactGroup::values())->toBe(['family', 'friends', 'business'])
        ->and(ContactGroup::Business->label())->toBe('Business');
});
