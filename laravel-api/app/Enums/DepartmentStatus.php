<?php

namespace App\Enums;

class DepartmentStatus
{
    const INACTIVE = 0;
    const ACTIVE = 1;
    const DRAFT = 2;
    const ARCHIVED = 3;

    /**
     * Get all status options as array
     *
     * @return array
     */
    public static function getAll(): array
    {
        return [
            self::INACTIVE => 'Inactive',
            self::ACTIVE => 'Active',
            self::DRAFT => 'Draft',
            self::ARCHIVED => 'Archived'
        ];
    }

    public static function getLabel(self|int $value): string
    {
        if (is_int($value)) {
            $value = self::tryFrom($value);
        }

        return match ($value) {
            self::INACTIVE => 'Inactive',
            self::ACTIVE => 'Active',
            self::DRAFT => 'Draft',
            self::ARCHIVED => 'Archived',
            default => '',
        };
    }

    public function label(): string
    {
        return self::getLabel($this);
    }
}
