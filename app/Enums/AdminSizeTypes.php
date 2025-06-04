<?php

namespace App\Enums;

enum AdminSizeTypes:INT
{
    case SIZE=0;
    case WEIGHT=1;
    case VOLUME=2;

    public static function values():array{
        return array_column(Self::cases(),'value');
    }

    public   function label():string{
        return match ($this){
            self::SIZE=>'Size',
            self::WEIGHT=>'Weight',
            self::VOLUME=>'Volume'
        };
    }

    public static function toArray(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ], self::cases());
    }

    


}
