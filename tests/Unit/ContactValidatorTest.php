<?php

declare(strict_types=1);

use App\Domain\ContactGroup;
use App\Validation\ContactValidator;
use App\Validation\ValidationException;

function validate(array $input): ContactInputOrErrors
{
    try {
        return new ContactInputOrErrors((new ContactValidator())->validate($input), []);
    } catch (ValidationException $e) {
        return new ContactInputOrErrors(null, $e->errors);
    }
}

final readonly class ContactInputOrErrors
{
    /** @param array<string, string> $errors */
    public function __construct(public ?App\Domain\ContactInput $input, public array $errors) {}
}

$valid = ['first_name' => 'Ada', 'last_name' => 'Lovelace', 'contact_group' => 'friends'];

it('accepts a minimal valid contact and normalises empty optionals to null', function () use ($valid) {
    $result = validate($valid + ['email' => '', 'notes' => '   ']);

    expect($result->errors)->toBe([])
        ->and($result->input->firstName)->toBe('Ada')
        ->and($result->input->group)->toBe(ContactGroup::Friends)
        ->and($result->input->email)->toBeNull()
        ->and($result->input->notes)->toBeNull();
});

it('trims whitespace and strips control characters', function () use ($valid) {
    $result = validate(['first_name' => "  Ada\x00\x07 "] + $valid);

    expect($result->input->firstName)->toBe('Ada');
});

it('keeps newlines in notes', function () use ($valid) {
    expect(validate($valid + ['notes' => "line one\r\nline two"])->input->notes)->toBe("line one\nline two");
});

it('requires first name, last name and group', function () {
    expect(validate([])->errors)->toHaveKeys(['first_name', 'last_name', 'contact_group']);
});

it('rejects an invalid email', function () use ($valid) {
    expect(validate($valid + ['email' => 'not-an-email'])->errors)->toHaveKey('email');
});

it('rejects a phone number with letters', function () use ($valid) {
    expect(validate($valid + ['phone' => 'call me maybe'])->errors)->toHaveKey('phone');
});

it('accepts common phone formats', function (string $phone) use ($valid) {
    expect(validate($valid + ['phone' => $phone])->errors)->toBe([]);
})->with(['+40 256 123 456', '(555) 010-0199', '0721.123.456', '+44 20 7946 0221']);

it('rejects an unknown group', function () use ($valid) {
    expect(validate(['contact_group' => 'enemies'] + $valid)->errors)->toHaveKey('contact_group');
});

it('enforces the column lengths', function () use ($valid) {
    expect(validate(['first_name' => str_repeat('a', 101)] + $valid)->errors)->toHaveKey('first_name')
        ->and(validate(['first_name' => str_repeat('ä', 100)] + $valid)->errors)->toBe([]);
});

it('rejects arrays where text is expected', function () use ($valid) {
    expect(validate(['first_name' => ['nested']] + $valid)->errors)->toHaveKey('first_name');
});
