<?php

declare(strict_types=1);

namespace App\Http\Session;

interface Session
{
    public function get(string $key): mixed;

    public function set(string $key, mixed $value): void;

    /** Returns the value and removes it, for one-time "flash" messages. */
    public function pull(string $key): mixed;
}
