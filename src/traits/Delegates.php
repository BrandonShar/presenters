<?php

namespace brandonshar\Traits;

trait Delegates
{
    public function __get($attr)
    {
        return $this->handleDelegates($attr);
    }

    protected function handleDelegates($attr)
    {
        if ($this->hasDelegateFor($attr)) {
            return $this->{$this->delegateFor($attr)}->$attr;
        }
    }

    protected function hasDelegateFor($attr)
    {
        return (bool) $this->delegateFor($attr);
    }

    private function delegateFor($attr)
    {
        foreach ($this->delegatesTo as $delegate => $attributes) {
            if (in_array($attr, $attributes)) {
                return $delegate;
            }
        }
    }

    
}