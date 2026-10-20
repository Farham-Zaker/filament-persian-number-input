<?php

namespace FarhamZaker\FilamentPersianNumberInput;

class NumberUtils
{
    private const ENGLISH = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

    private const PERSIAN = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

    private const ARABIC = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

    public static function toPersian(string|int|float|null $value): string
    {
        if ($value === null) {
            return '';
        }

        return str_replace(
            self::ENGLISH,
            self::PERSIAN,
            (string) $value
        );
    }

    public static function toEnglish(string|int|float $value): string
    {
        return str_replace(
            self::ARABIC,
            self::ENGLISH,
            str_replace(
                self::PERSIAN,
                self::ENGLISH,
                (string) $value
            )
        );
    }

    public static function format(
        string|int|float|null $value,
        int $groupBy = 3,
        bool $persian = true
    ): string {
        if ($value === null || $value === '') {
            return '';
        }

        $formatted = number_format((float) $value, 0, '.', ',');

        return $persian
            ? self::toPersian($formatted)
            : $formatted;
    }
}
