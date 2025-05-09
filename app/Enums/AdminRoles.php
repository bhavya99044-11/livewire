<?php

namespace App\Enums;

enum AdminRoles: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    
}
