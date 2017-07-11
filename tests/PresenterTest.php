<?php

namespace Tests;

use brandonshar\Presenter;
use PHPUnit\Framework\TestCase;

class PresenterTest extends TestCase
{
    public function test_it_turns_to_json()
    {
        $obj = (object) ['title' => 'my title'];
        $presenter = BasicPresenter::present($obj)->tap(function($p) {
            $p->data = 'some data';
            $p->test = 'my test';
        });

        $this->assertEquals(
            json_decode(json_encode($presenter), true),
            [
                'data' => 'some data',
                'title' => 'my title',
                'test' => 'MY TEST',
                'read_only' => 'some attribute',
            ]
        );
    }
}

class BasicPresenter extends Presenter {
    
    protected $obj;
    protected $delegatesTo = [
        'obj' => ['title']
    ];

    public function __construct($obj)
    {
        $this->obj = $obj;
    }

    public function getReadOnlyAttribute()
    {
        return 'some attribute';
    }

    public function getTestAttribute($test)
    {
        return strtoupper($test);
    }
}
