<?php

namespace Fabrika\Generator;

class RandomNumberTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceOf()
    {
        $generator = new RandomNumber(0, 0);
        $this->assertInstanceOf('Fabrika\IGenerator', $generator);
    }

    public function testGenerate()
    {
        $generator = new RandomNumber(0, 3);
        $this->assertContains($generator->generate(), array(0, 1, 2, 3));
        $this->assertContains($generator->generate(), array(0, 1, 2, 3));
        $this->assertContains($generator->generate(), array(0, 1, 2, 3));
        $this->assertContains($generator->generate(), array(0, 1, 2, 3));
        $this->assertContains($generator->generate(), array(0, 1, 2, 3));
    }
}
