<?php

namespace App\Enums;

enum ProductSizeTypes: String
{
    case SIZE=0;
    case WEIGHT=1;

    public static function values(){
        return array_column(self::cases(),'value');
    }

    public function label():String
    {
        return match ($this){
            self::SIZE=>'size',
            self::WEIGHT=>'weight'
        };
    }
}
