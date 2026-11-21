<?php

declare(strict_types=1);

namespace App\Http;

use App\Http\Session\Session;

/** Synchroniser-token CSRF protection: one random token per session. */
final class Csrf
{
    public const string FIELD = '_csrf';
    public const string HEADER = 'X-CSRF-Token';
    private const string SESSION_KEY = 'csrf_token';

    public function __construct(private readonly Session $session) {}

    public function token(): string
    {
        $token = $this->session->get(self::SESSION_KEY);
        if (!is_string($token) || $token === '') {
            $token = bin2hex(random_bytes(32));
            $this->session->set(self::SESSION_KEY, $token);
        }

        return $token;
    }

    public function isValid(?string $candidate): bool
    {
        return is_string($candidate) && $candidate !== '' && hash_equals($this->token(), $candidate);
    }
}
