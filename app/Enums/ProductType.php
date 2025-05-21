<?php

namespace App\Enums;

enum ProductType:INt 
{
    case ADDON=0;
    case OPTIONS=1;
    case VARIANT=2;

    public function label(): string
    {
        return match($this){
            self::ADDON => 'Addon',
            self::OPTIONS => 'Options',
            self::VARIANT => 'Variant',
        };
    }

    public static function values(){
        return array_column(self::cases(),'value');
    }

    public static function toArray(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ], self::cases());
    }

    
}
