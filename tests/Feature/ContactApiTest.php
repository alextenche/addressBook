<?php

declare(strict_types=1);

it('lists contacts as JSON, sorted by last name', function () {
    $this->createContact(['first_name' => 'Zed', 'last_name' => 'Zulu']);
    $this->createContact(['first_name' => 'Amy', 'last_name' => 'Alpha']);

    $response = $this->json('GET', '/contacts');

    expect($response->getStatusCode())->toBe(200)
        ->and(array_column($this->decode($response)['data'], 'last_name'))->toBe(['Alpha', 'Zulu']);
});

it('searches by name, email, phone and city', function () {
    $this->createContact(['first_name' => 'Grace', 'last_name' => 'Hopper', 'city' => 'Arlington']);
    $this->createContact(['first_name' => 'Alan', 'last_name' => 'Turing', 'email' => 'alan@bletchley.example']);

    expect($this->decode($this->json('GET', '/contacts?q=grace hop'))['data'])->toHaveCount(1)
        ->and($this->decode($this->json('GET', '/contacts?q=bletchley'))['data'])->toHaveCount(1)
        ->and($this->decode($this->json('GET', '/contacts?q=arlington'))['data'])->toHaveCount(1)
        ->and($this->decode($this->json('GET', '/contacts?q=nobody'))['data'])->toHaveCount(0);
});

it('treats LIKE wildcards in a search as literal text', function () {
    $this->createContact(['first_name' => 'Percent', 'notes' => 'x']);

    expect($this->decode($this->json('GET', '/contacts?q=%25'))['data'])->toHaveCount(0)
        ->and($this->decode($this->json('GET', '/contacts?q=_'))['data'])->toHaveCount(0);
});

it('filters by group', function () {
    $this->createContact(['contact_group' => 'family']);
    $this->createContact(['contact_group' => 'business']);

    $data = $this->decode($this->json('GET', '/contacts?group=family'))['data'];

    expect($data)->toHaveCount(1)->and($data[0]['contact_group'])->toBe('family');
});

it('creates a contact', function () {
    $response = $this->json('POST', '/contacts', $this->validPayload());
    $data = $this->decode($response)['data'];

    expect($response->getStatusCode())->toBe(201)
        ->and($response->getHeaderLine('Location'))->toBe("/contacts/{$data['id']}")
        ->and($data['first_name'])->toBe('Grace')
        ->and($data['address_line1'])->toBeNull();
});

it('returns field errors for invalid input', function () {
    $response = $this->json('POST', '/contacts', ['email' => 'nope', 'contact_group' => 'enemies']);

    expect($response->getStatusCode())->toBe(422)
        ->and($this->decode($response)['errors'])->toHaveKeys(['first_name', 'last_name', 'email', 'contact_group']);
});

it('rejects writes without a valid CSRF token', function (string $method, string $uri) {
    $contact = $this->createContact();
    $uri = str_replace('{id}', (string) $contact->id, $uri);

    $response = $this->json($method, $uri, $this->validPayload(), ['X-CSRF-Token' => 'forged']);

    expect($response->getStatusCode())->toBe(403)
        ->and($this->decode($this->json('GET', '/contacts'))['data'])->toHaveCount(1);
})->with([
    'create' => ['POST', '/contacts'],
    'update' => ['PUT', '/contacts/{id}'],
    'delete' => ['DELETE', '/contacts/{id}'],
]);

it('shows a single contact', function () {
    $contact = $this->createContact();

    expect($this->decode($this->json('GET', "/contacts/{$contact->id}"))['data']['email'])->toBe('ada@example.com');
});

it('updates a contact', function () {
    $contact = $this->createContact();

    $response = $this->json('PUT', "/contacts/{$contact->id}", ['first_name' => 'Augusta'] + $this->validPayload());

    expect($response->getStatusCode())->toBe(200)
        ->and($this->decode($response)['data']['first_name'])->toBe('Augusta');
});

it('deletes a contact', function () {
    $contact = $this->createContact();

    expect($this->json('DELETE', "/contacts/{$contact->id}")->getStatusCode())->toBe(204)
        ->and($this->json('GET', "/contacts/{$contact->id}")->getStatusCode())->toBe(404);
});

it('returns 404 for unknown contacts', function (string $method) {
    expect($this->json($method, '/contacts/999999', $method === 'PUT' ? $this->validPayload() : null)->getStatusCode())->toBe(404);
})->with(['GET', 'PUT', 'DELETE']);
