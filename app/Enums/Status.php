<?php

namespace App\Enums;

enum Status: Int
{
    case ACTIVE = 1;
    case INACTIVE = 0;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function color(): string
    {
        return match ($this) {
            self::ACTIVE => '#009E60',
            self::INACTIVE => '#FF5733'
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive'
        };
    }
    public static function toJsObject(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [
            $case->value => [
                'label' => $case->label(),
                'value'=>$case->value
            ]
        ])->toArray();
    }

    public static function toArray(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ], self::cases());
    }
}
