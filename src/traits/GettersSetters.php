<?php

namespace brandonshar\Traits;

use brandonshar\Str;

trait GettersSetters
{
    protected $attributes = [];

    public function __get($attr)
    {
        return $this->handleGetters($attr);
    }

    public function __set($attr, $value)
    {
        $this->handleSetters($attr, $value);
    }

    protected function handleGetters($attr)
    {
        if ($this->hasGetter($attr)) {
            return $this->{$this->getGetterMethodName($attr)}($this->getAttribute($attr));
        }

        return $this->getAttribute($attr);
    }

    protected function handleSetters($attr, $value)
    {
        if ($this->hasSetter($attr)) {
            $this->{$this->getSetterMethodName($attr)}($value);
        } else {
            $this->setAttribute($attr, $value);
        }
    }

    protected function setAttribute($key, $attr)
    {
        $this->attributes[Str::snake($key)] = $attr;
    }

    protected function getAttribute($key)
    {
        return $this->attributes[Str::snake($key)] ?? null;
    }

    private function getGetterMethodName($attr)
    {
        return 'get' . Str::studly($attr) . 'Attribute';
    }

    private function hasGetter($attr)
    {
        return method_exists($this, $this->getGetterMethodName($attr));
    }

    private function getSetterMethodName($attr)
    {
        return 'set' . Str::studly($attr) . 'Attribute';
    }

    private function hasSetter($attr)
    {
        return method_exists($this, $this->getSetterMethodName($attr));
    }
}
