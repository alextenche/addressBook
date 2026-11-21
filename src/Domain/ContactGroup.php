<?php

declare(strict_types=1);

namespace App\Domain;

enum ContactGroup: string
{
    case Family = 'family';
    case Friends = 'friends';
    case Business = 'business';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    /** @return list<string> */
    public static function values(): array
    {
        return array_map(static fn(self $group): string => $group->value, self::cases());
    }
}
