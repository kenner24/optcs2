<?php

namespace App\Enums;


enum MonthEnum: string
{
    case JANUARY = 'Jan';
    case FEBRUARY = 'Feb';
    case MARCH = 'Mar';
    case APRIL = 'Apr';
    case MAY = 'May';
    case JUNE = 'Jun';
    case JULY = 'Jul';
    case AUGUST = 'Aug';
    case SEPTEMBER = 'Sep';
    case OCTOBER = 'Oct';
    case NOVEMBER = 'Nov';
    case DECEMBER = 'Dec';

    public static function getAllValues(): array
    {
        return array_column(MonthEnum::cases(), 'value');
    }
}
