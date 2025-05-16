<?php

namespace App\Enums;

enum ApproveStatus: Int
{
    case APPROVED=1;
    case PENDING=0;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::APPROVED => 'Approved',
            self::PENDING => 'Pending'
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
