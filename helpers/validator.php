<?php

class validator
{
    public function is_empty($input)
    {
        return empty($input);
    }

    public static function Check_Required_Fields(array $expected_fields, $input = null)
    {
        $empty_fields = [];
        if (is_null($input)) {
            return;
        }
        if (is_array($input)) {
            if (!\count($expected_fields)) {
                return;
            }
            foreach ($expected_fields as $fields) {
                if (!array_key_exists($fields, $input) || empty($fields)) {
                    array_push($empty_fields, $fields);
                }
            }

            return $empty_fields;
        }
        if (is_object($input)) {
            foreach ($expected_fields as $fields) {
                if (!property_exists($input, $fields) || empty($fields)) {
                    array_push($empty_fields, $fields);
                }
            }

            return $empty_fields;
        }

        return 'Invalid parameter received';
    }

    public static function Is_Too_Long($input, int $length)
    {
        return strlen($input) > $length;
    }

    public static function Is_Too_Short($input, int $length)
    {
        return strlen($input) < $length;
    }

    public static function Is_Exact_Length($input, int $length)
    {
        return strlen($input) === $length;
    }

    public static function Is_Number($input)
    {
        return is_numeric($input);
    }
}
