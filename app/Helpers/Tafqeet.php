<?php

namespace App\Helpers;

class Tafqeet
{
    private static array $ones = [
        0 => '',
        1 => 'واحد',
        2 => 'اثنان',
        3 => 'ثلاثة',
        4 => 'أربعة',
        5 => 'خمسة',
        6 => 'ستة',
        7 => 'سبعة',
        8 => 'ثمانية',
        9 => 'تسعة',
        10 => 'عشرة',
        11 => 'أحد عشر',
        12 => 'اثنا عشر',
        13 => 'ثلاثة عشر',
        14 => 'أربعة عشر',
        15 => 'خمسة عشر',
        16 => 'ستة عشر',
        17 => 'سبعة عشر',
        18 => 'ثمانية عشر',
        19 => 'تسعة عشر',
    ];

    private static array $tens = [
        2 => 'عشرون',
        3 => 'ثلاثون',
        4 => 'أربعون',
        5 => 'خمسون',
        6 => 'ستون',
        7 => 'سبعون',
        8 => 'ثمانون',
        9 => 'تسعون',
    ];

    private static array $hundreds = [
        0 => '',
        1 => 'مائة',
        2 => 'مائتان',
        3 => 'ثلاثمائة',
        4 => 'أربعمائة',
        5 => 'خمسمائة',
        6 => 'ستمائة',
        7 => 'سبعمائة',
        8 => 'ثمانمائة',
        9 => 'تسعمائة',
    ];

    public static function convert($number, string $currency = 'شيكل', string $fraction = 'أغورة'): string
    {
        $number = (float) str_replace(',', '', (string)$number);
        if ($number <= 0) {
            return 'صفر ' . $currency;
        }

        $integerPart = (int) floor($number);
        $fractionPart = (int) round(($number - $integerPart) * 100);

        $words = self::convertInteger($integerPart);
        $result = $words . ' ' . $currency;

        if ($fractionPart > 0) {
            $fractionWords = self::convertInteger($fractionPart);
            $result .= ' و' . $fractionWords . ' ' . $fraction;
        }

        return trim($result);
    }

    public static function convertInteger(int $number): string
    {
        if ($number === 0) {
            return 'صفر';
        }

        if ($number < 0) {
            return 'سالب ' . self::convertInteger(abs($number));
        }

        $parts = [];

        // Millions
        if ($number >= 1000000) {
            $millions = (int) floor($number / 1000000);
            $number %= 1000000;
            if ($millions === 1) {
                $parts[] = 'مليون';
            } elseif ($millions === 2) {
                $parts[] = 'مليونان';
            } elseif ($millions >= 3 && $millions <= 10) {
                $parts[] = self::convertGroup($millions) . ' ملايين';
            } else {
                $parts[] = self::convertGroup($millions) . ' مليوناً';
            }
        }

        // Thousands
        if ($number >= 1000) {
            $thousands = (int) floor($number / 1000);
            $number %= 1000;
            if ($thousands === 1) {
                $parts[] = 'ألف';
            } elseif ($thousands === 2) {
                $parts[] = 'ألفان';
            } elseif ($thousands >= 3 && $thousands <= 10) {
                $parts[] = self::convertGroup($thousands) . ' آلاف';
            } else {
                $parts[] = self::convertGroup($thousands) . ' ألفاً';
            }
        }

        // Remainder (0 - 999)
        if ($number > 0) {
            $parts[] = self::convertGroup($number);
        }

        return implode(' و', $parts);
    }

    private static function convertGroup(int $number): string
    {
        if ($number === 0) {
            return '';
        }

        $parts = [];

        // Hundreds
        $h = (int) floor($number / 100);
        $remainder = $number % 100;

        if ($h > 0) {
            $parts[] = self::$hundreds[$h];
        }

        if ($remainder > 0) {
            if ($remainder <= 19) {
                $parts[] = self::$ones[$remainder];
            } else {
                $o = $remainder % 10;
                $t = (int) floor($remainder / 10);
                if ($o > 0) {
                    $parts[] = self::$ones[$o] . ' و' . self::$tens[$t];
                } else {
                    $parts[] = self::$tens[$t];
                }
            }
        }

        return implode(' و', $parts);
    }
}
