<?php

namespace App\Services;

class StringConverter
{
    public static function removeAccents(string $string): string
    {
        $unwanted_array = ['Ά' => 'Α', 'Έ' => 'Ε', 'Ή' => 'Η', 'Ί' => 'Ι', 'Ϊ' => 'Ι', 'Ό' => 'Ο', 'Ύ' => 'Υ', 'Ώ' => 'Ω',
            'Ϋ' => 'Υ', 'ά' => 'α', 'έ' => 'ε', 'ή' => 'η', 'ί' => 'ι', 'ϊ' => 'ι', 'ΐ' => 'ι', 'ό' => 'ο',
            'ύ' => 'υ', 'ΰ' => 'υ', 'ώ' => 'ω'];
        $string = strtr($string, $unwanted_array);

        return $string;
    }

    /**
     * Normalize a string the way MariaDB utf8mb4_unicode_ci compares it:
     * trailing/leading whitespace ignored (PAD SPACE + TrimStrings),
     * accents stripped, case folded (final sigma folds via uppercasing).
     */
    public static function normalizeForComparison(string $string): string
    {
        return mb_strtoupper(self::removeAccents(trim($string)));
    }

    /**
     * Whether two strings match under utf8mb4_unicode_ci semantics.
     */
    public static function namesMatch(string $a, string $b): bool
    {
        return self::normalizeForComparison($a) === self::normalizeForComparison($b);
    }
}
