<?php

namespace App\Services;

class StringConverter {
    public static function removeAccents(string $string): string
    {
        $unwanted_array = array('Ά' => 'Α', 'Έ' => 'Ε', 'Ί' => 'Ι', 'Ϊ' => 'Ι', 'Ό' => 'Ο', 'Ύ' => 'Υ', 'Ϋ' => 'Υ',
                                'ά' => 'α', 'έ' => 'ε', 'ί' => 'ι', 'ϊ' => 'ι', 'ΐ' => 'ι', 'ό' => 'ο', 'ύ' => 'υ',
                                'ΰ' => 'υ');
        $string = strtr($string, $unwanted_array);
        return $string;
    }
}
