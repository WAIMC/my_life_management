<?php

declare(strict_types=1);

namespace App\Enums;

enum DepartmentStatus: int
{
    /**
     * inactive
     *
     * @var int
     */
    case INACTIVE = 0;

    /**
     * active
     *
     * @var int
     */
    case ACTIVE = 1;

    /**
     * planned
     *
     * @var int
     */
    case PLANNED = 2;

    public static function getLabel(self|int $value): string
    {
        if (is_int($value)) {
            $value = self::tryFrom($value);
        }

        return match ($value) {
            self::INACTIVE => 'inactive',
            self::ACTIVE => 'active',
            self::PLANNED => 'planned',
            default => '',
        };
    }

    public function label(): string
    {
        return self::getLabel($this);
    }

    public static function toArray(): array
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = self::getLabel($case);
            return $carry;
        }, []);
    }
}
