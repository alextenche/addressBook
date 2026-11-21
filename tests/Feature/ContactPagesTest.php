<?php

declare(strict_types=1);

it('renders the contact list', function () {
    $this->createContact();

    $response = $this->html('GET', '/');
    $body = (string) $response->getBody();

    expect($response->getStatusCode())->toBe(200)
        ->and($body)->toContain('Ada Lovelace')
        ->and($body)->toContain('name="csrf-token"')
        ->and($body)->toContain('1 contact');
});

it('shows an empty state', function () {
    expect((string) $this->html('GET', '/')->getBody())->toContain('Your address book is empty');
});

it('escapes contact data in HTML (no stored XSS)', function () {
    $this->createContact(['first_name' => '<script>alert(1)</script>', 'notes' => '"><img src=x onerror=alert(1)>']);

    $body = (string) $this->html('GET', '/')->getBody();

    expect($body)->not->toContain('<script>alert(1)</script>')
        ->and($body)->not->toContain('<img src=x')
        ->and($body)->toContain('&lt;script&gt;alert(1)&lt;/script&gt;');
});

it('returns only the list for ?fragment=list', function () {
    $this->createContact();

    $body = (string) $this->html('GET', '/?fragment=list')->getBody();

    expect($body)->toContain('Ada Lovelace')->and($body)->not->toContain('<html');
});

it('sends security headers', function () {
    $response = $this->html('GET', '/');

    expect($response->getHeaderLine('Content-Security-Policy'))->toContain("script-src 'self'")
        ->and($response->getHeaderLine('X-Content-Type-Options'))->toBe('nosniff')
        ->and($response->getHeaderLine('X-Frame-Options'))->toBe('DENY');
});

it('creates a contact from a plain HTML form and redirects with a message', function () {
    $response = $this->html('POST', '/contacts', ['_csrf' => $this->csrfToken()] + $this->validPayload());

    expect($response->getStatusCode())->toBe(303)
        ->and($response->getHeaderLine('Location'))->toBe('/')
        ->and((string) $this->html('GET', '/')->getBody())->toContain('Added Grace Hopper.');
});

it('re-renders the form with errors and the submitted values', function () {
    $response = $this->html('POST', '/contacts', [
        '_csrf' => $this->csrfToken(), 'first_name' => '"><b>Grace', 'email' => 'bad',
    ]);
    $body = (string) $response->getBody();

    expect($response->getStatusCode())->toBe(422)
        ->and($body)->toContain('Email must be a valid email address.')
        ->and($body)->toContain('value="&quot;&gt;&lt;b&gt;Grace"');
});

it('rejects a plain form post without a CSRF token', function () {
    expect($this->html('POST', '/contacts', $this->validPayload())->getStatusCode())->toBe(403);
});

it('updates and deletes through method-override forms', function () {
    $contact = $this->createContact();
    $csrf = $this->csrfToken();

    $update = $this->html('POST', "/contacts/{$contact->id}", ['_METHOD' => 'PUT', '_csrf' => $csrf, 'first_name' => 'Augusta'] + $this->validPayload());
    expect($update->getStatusCode())->toBe(303)
        ->and((string) $this->html('GET', '/')->getBody())->toContain('Saved Augusta Hopper.');

    $delete = $this->html('POST', "/contacts/{$contact->id}", ['_METHOD' => 'DELETE', '_csrf' => $csrf]);
    expect($delete->getStatusCode())->toBe(303)
        ->and((string) $this->html('GET', '/')->getBody())->toContain('Your address book is empty');
});

it('renders the add and edit pages', function () {
    $contact = $this->createContact();

    expect((string) $this->html('GET', '/contacts/new')->getBody())->toContain('Add contact')
        ->and((string) $this->html('GET', "/contacts/{$contact->id}/edit")->getBody())->toContain('value="Lovelace"');
});
