<?php

declare(strict_types=1);

namespace App\Http\Session;

/** PHP's built-in session, started lazily with hardened cookie settings. */
final class NativeSession implements Session
{
    public function __construct(private readonly bool $secureCookie = false) {}

    public function get(string $key): mixed
    {
        $this->start();

        return $_SESSION[$key] ?? null;
    }

    public function set(string $key, mixed $value): void
    {
        $this->start();
        $_SESSION[$key] = $value;
    }

    public function pull(string $key): mixed
    {
        $value = $this->get($key);
        unset($_SESSION[$key]);

        return $value;
    }

    private function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }
        session_name('addressbook');
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'secure' => $this->secureCookie,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        ini_set('session.use_strict_mode', '1');
        session_start();
    }
}
