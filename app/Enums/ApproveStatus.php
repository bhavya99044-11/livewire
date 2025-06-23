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

    public function color()
    {
        return match ($this) {
            self::APPROVED => '#16a34a',
            self::PENDING => '#ca8a04',
            self::REJECT  => '#dc2626',
        };
    }
    
    public function bgColor()
    {
        return match ($this) {
            self::APPROVED => '#dcfce7',
            self::PENDING => '#fef9c3',
            self::REJECT  => '#fee2e2',
        };
    }
    
}
