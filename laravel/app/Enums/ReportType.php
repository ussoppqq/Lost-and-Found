<?php

namespace App\Enums;

enum ReportType: string
{
    case LOST = 'LOST';
    case FOUND = 'FOUND';

    public function label(): string
    {
        return match($this) {
            self::LOST => 'Lost',
            self::FOUND => 'Found',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::LOST => 'bg-red-100 text-red-800',
            self::FOUND => 'bg-green-100 text-green-800',
        };
    }

    public static function options(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label()
        ], self::cases());
    }
}