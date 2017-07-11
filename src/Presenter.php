<?php

namespace brandonshar;

use JsonSerializable;
use brandonshar\Traits\Delegates;
use brandonshar\Traits\GettersSetters;

class Presenter implements JsonSerializable
{
    use Delegates;
    use GettersSetters;

    public static function present(...$args)
    {
        return new static(...$args);
    }

    public function __get($attr)
    {
        return $this->handleGetters($attr) ?? $this->handleDelegates($attr);
    }

    public function tap(callable $callback)
    {
        $callback($this);

        return $this;
    }

    public function jsonSerialize()
    {
        $results = [];

        foreach ($this->delegatesTo as $delegate => $attributes) {
            foreach ($attributes as $attribute) {
                $results[$attribute] = $this->$attribute;
            }
        }

        $results = array_merge($results, $this->attributes);

        foreach (get_class_methods($this) as $method) {
            if (substr($method, 0, 3) === 'get' && substr($method, -9) === 'Attribute') {
                $attribute = Str::snake(substr($method, 3, strlen($method) - 12));
                if ($attribute) {
                    $results[$attribute] = $this->$attribute;
                }
            }
        }

        return $results;
    }

}
