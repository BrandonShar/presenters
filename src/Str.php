<?php

namespace brandonshar;

class Str
{
    private static $snakeCache = [];
    private static $studlyCache = [];
    private static $camelCache = [];

    public static function snake($value)
    {

        return Str::$snakeCache[$value] ?? 
            (Str::$snakeCache[$value] = ctype_lower($value) 
                ? $value 
                : strtolower(preg_replace('/(.)(?=[A-Z])/', '$1'.'_', $value)));
    }

    public static function studly($value)
    {
        return Str::$studlyCache[$value] ?? 
            (Str::$studlyCache[$value] = str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $value))));
    }

    public static function camel($value)
    {
        return Str::$camelCache[$value] ?? (Str::$camelCache[$value] = lcfirst(Str::studly($value)));
    }
}