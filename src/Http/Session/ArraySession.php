<?php

declare(strict_types=1);

namespace App\Http\Session;

/** In-memory session for tests. */
final class ArraySession implements Session
{
    /** @var array<string, mixed> */
    private array $data = [];

    public function get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    public function set(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    public function pull(string $key): mixed
    {
        $value = $this->get($key);
        unset($this->data[$key]);

        return $value;
    }
}
