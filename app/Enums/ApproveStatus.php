<?php

namespace App\Enums;

enum ApproveStatus: Int
{
    case PENDING=0;
    case APPROVED=1;
    case REJECT=2;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::APPROVED => 'Approved',
            self::PENDING => 'Pending',
            self::REJECT=> 'Reject'
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

    public function color(){
        return match ($this) {
            self::APPROVED => 'text-green-500',
            self::PENDING => 'text-yellow-500',
            self::REJECT => 'text-red-500',
        };
    }
    public function bgColor(){
        return match ($this) {
            self::APPROVED => 'bg-green-100',
            self::PENDING => 'bg-yellow-100',
            self::REJECT => 'bg-red-100',
        };
    }
}
