<?php

namespace Fabrika\Generator;

class IntegerSequenceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceOf()
    {
        $generator = new IntegerSequence();
        $this->assertInstanceOf('Fabrika\IGenerator', $generator);
    }

    public function testGenerate()
    {
        $generator = new IntegerSequence();
        $this->assertEquals(1, $generator->generate());
        $this->assertEquals(2, $generator->generate());
    }

    public function testGenerateCustomStep()
    {
        $generator = new IntegerSequence(2);
        $this->assertEquals(2, $generator->generate());
        $this->assertEquals(4, $generator->generate());
    }

    public function testOnFlush()
    {
        $generator = new IntegerSequence();
        $this->assertEquals(1, $generator->generate());
        $this->assertEquals(2, $generator->generate());
        $generator->onFlush();
        $this->assertEquals(1, $generator->generate());
    }

    public function testOnIncrementCounter()
    {
        $generator = new IntegerSequence();
        $this->assertEquals(1, $generator->generate());
        $generator->onIncrementCounter();
        $this->assertEquals(3, $generator->generate());
        $generator->onIncrementCounter(5);
        $this->assertEquals(9, $generator->generate());
        $generator->onIncrementCounter(-10);
        $this->assertEquals(10, $generator->generate());
    }
}
