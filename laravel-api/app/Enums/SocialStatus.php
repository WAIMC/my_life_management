<?php

namespace App\Enums;

abstract class SocialStatus
{
    /**
     * Inactive status
     */
    const INACTIVE = 0;
    
    /**
     * Active status
     */
    const ACTIVE = 1;
    
    /**
     * Pending status
     */
    const PENDING = 2;
    
    /**
     * Get all status
     * 
     * @return array
     */
    public static function getAll(): array
    {
        return [
            self::INACTIVE => 'Inactive',
            self::ACTIVE => 'Active',
            self::PENDING => 'Pending',
        ];
    }
}
