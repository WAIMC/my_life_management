<?php

declare(strict_types=1);

namespace App\Enums;

enum Gender: int
{
    /**
     * male
     *
     * @var int
     */
    case MALE = 0;

    /**
     * female
     *
     * @var int
     */
    case FEMALE = 1;

    public static function getLabel(self|int $value): string
    {
        if (is_int($value)) {
            $value = self::tryFrom($value);
        }

        return match ($value) {
            self::MALE => 'male',
            self::FEMALE => 'female',
            default => '',
        };
    }

    public function label(): string
    {
        return self::getLabel($this);
    }
}
