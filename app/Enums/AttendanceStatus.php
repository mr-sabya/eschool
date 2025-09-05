<?php
// app/Enums/AttendanceStatus.php
namespace App\Enums;

enum AttendanceStatus: string
{
    case PRESENT = 'present';
    case ABSENT  = 'absent';
    case LATE    = 'late';
    case LEAVE   = 'leave';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
