<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use brandonshar\Traits\Delegates;

class DelegatesTest extends TestCase
{
    private $stub;
    private $a;
    private $b;

    public function setUp()
    {
        parent::setUp();

        $this->a = new DelegateeA;
        $this->b = new DelegateeB;
        $this->stub = new DelegatesStub(
            $this->a,
            $this->b
        );
    }

    public function test_it_delegates_to_the_correct_method()
    {
        $this->stub->delegatesTo = [
            'a' => ['title', 'description'],
            'b' => ['something', 'somethingElse'],
        ];

        $this->assertEquals($this->stub->title, 'the title');
        $this->assertEquals($this->stub->description, 'lorem ipsum');
        $this->assertEquals($this->stub->something, 'hello');
        $this->assertEquals($this->stub->somethingElse, 'something else');

    }
}

class DelegatesStub
{
    use Delegates;

    public $a;
    public $b;
    public $delegatesTo = [];

    public function __construct($a, $b)
    {
        $this->a = $a;
        $this->b = $b;
    }
}

class DelegateeA
{
    public $title = 'the title';
    public $description = 'lorem ipsum';
}

class DelegateeB
{
    public $something = 'hello';
    public $somethingElse = 'something else';
}