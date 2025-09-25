<?php

declare(strict_types=1);

namespace App\Enums;

enum StatusEnum: int
{
    /**
     * draft
     *
     * @var int
     */
    case DRAFT = 0;

    /**
     * published
     *
     * @var int
     */
    case PUBLISHED = 1;

    /**
     * archived
     *
     * @var int
     */
    case ARCHIVED = 2;

    public static function getLabel(self|int $value): string
    {
        if (is_int($value)) {
            $value = self::tryFrom($value);
        }

        return match ($value) {
            self::DRAFT => 'draft',
            self::PUBLISHED => 'published',
            self::ARCHIVED => 'archived',
            default => '',
        };
    }

    public function label(): string
    {
        return self::getLabel($this);
    }
}
