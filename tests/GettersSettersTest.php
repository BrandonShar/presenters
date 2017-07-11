<?php

namespace Tests;

use brandonshar\Traits\GettersSetters;
use PHPUnit\Framework\TestCase;

class GettersSettersTest extends TestCase
{
    public function test_it_gets_without_setter()
    {
        $stub = new GettersSettersStub;
        $stub->title = 'test title';

        $this->assertEquals($stub->title, 'getting: test title');
    }

    public function test_it_gets_flexibly()
    {
        $stub = new GettersSettersStub;

        $this->assertEquals($stub->read_only, 'hello there');
        $this->assertEquals($stub->readOnly, 'hello there');
    }

    public function test_it_sets_flexibly()
    {
        $stub = new GettersSettersStub;
        $stub->someValue = 'some value';
        $stub->other_value = 'other value';

        $this->assertEquals($stub->some_value, 'some value');
        $this->assertEquals($stub->otherValue, 'other value');
    }

    public function test_it_sets_without_getter()
    {
        $stub = new GettersSettersStub;
        $stub->description = 'the test description';

        $this->assertEquals($stub->description, 'setting: the test description');
    }

    public function test_it_gets_and_sets()
    {
        $stub = new GettersSettersStub;
        $stub->other = 'some great text';

        $this->assertEquals($stub->other, 'I GOT SOME GREAT TEXT');
    }
}

class GettersSettersStub 
{
    use GettersSetters;

    public function getReadOnlyAttribute()
    {
        return 'hello there';
    }

    public function getTitleAttribute($title)
    {
        return "getting: {$title}";
    }

    public function setOtherAttribute($value)
    {
        $this->setAttribute('other', strtoupper($value));
    }

    public function getOtherAttribute($value)
    {
        return "I GOT {$value}";
    }

    public function setDescriptionAttribute($value)
    {
        $this->setAttribute('description', "setting: {$value}");
    }
}