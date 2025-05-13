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

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::ADMIN => 'Admin'
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ADMIN => '#008080',
            self::SUPER_ADMIN => '#FF5733'
        };
    }

    
    public function bgColor(): string
    {
        return match ($this) {
            self::ADMIN => 'rgba(0, 128, 128, 0.2)',
            self::SUPER_ADMIN => 'rgba(255, 87, 51, 0.2)'
        };
    }
}
