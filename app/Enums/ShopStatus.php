<?php

namespace App\Enums;

enum ShopStatus: Int
{
    case OPEN =  1;
    case CLOSE=0;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::CLOSE => 'Close',
            self::OPEN => 'Open'
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
}
