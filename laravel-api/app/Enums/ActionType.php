<?php

namespace App\Enums;

class ActionType
{
    public const CREATE = 1;
    public const UPDATE = 2;
    public const DELETE = 3;

    public static function getLabel(self|int $value): string
    {
        if (is_int($value)) {
            $value = self::tryFrom($value);
        }

        return match ($value) {
            self::CREATE => 'Create',
            self::UPDATE => 'Update',
            self::DELETE => 'Delete',
            default => '',
        };
    }

    public function label(): string
    {
        return self::getLabel($this);
    }
}
