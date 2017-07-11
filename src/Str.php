<?php

namespace brandonshar;

class Str
{
    public static function snake($value)
    {
        return ctype_lower($value) 
            ? $value 
            : strtolower(preg_replace('/(.)(?=[A-Z])/', '$1'.'_', $value));
    }

    public static function studly($value)
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $value)));
    }
}