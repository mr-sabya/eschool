<?php

namespace App\Helpers;

class NumberHelper
{
    /**
     * Returns the ordinal suffix for a given number (st, nd, rd, th).
     *
     * @param int $number
     * @return string
     */
    public static function getOrdinalSuffix(int $number): string
    {
        if (!in_array(($number % 100), [11, 12, 13])) {
            switch ($number % 10) {
                case 1:  return 'st';
                case 2:  return 'nd';
                case 3:  return 'rd';
            }
        }
        return 'th';
    }
}