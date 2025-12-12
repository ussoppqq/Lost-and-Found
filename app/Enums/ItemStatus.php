<?php

namespace App\Enums;

enum ItemStatus: string
{
    case REGISTERED = 'REGISTERED';
    case STORED = 'STORED';
    case CLAIMED = 'CLAIMED';
    case DISPOSED = 'DISPOSED';
    case RETURNED = 'RETURNED';

    public function label(): string
    {
        return match($this) {
            self::REGISTERED => 'Registered',
            self::STORED => 'Stored',
            self::CLAIMED => 'Claimed',
            self::DISPOSED => 'Disposed',
            self::RETURNED => 'Returned',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::REGISTERED => 'bg-blue-100 text-blue-800',
            self::STORED => 'bg-yellow-100 text-yellow-800',
            self::CLAIMED => 'bg-purple-100 text-purple-800',
            self::DISPOSED => 'bg-red-100 text-red-800',
            self::RETURNED => 'bg-green-100 text-green-800',
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