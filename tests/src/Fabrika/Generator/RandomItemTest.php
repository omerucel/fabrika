<?php

namespace Fabrika\Generator;

class RandomItemTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceOf()
    {
        $generator = new RandomItem(array());
        $this->assertInstanceOf('Fabrika\IGenerator', $generator);
    }

    public function testGenerate()
    {
        $generator = new RandomItem(array('a', 'b', 'c'));
        $this->assertContains($generator->generate(), array('a', 'b', 'c'));
        $this->assertContains($generator->generate(), array('a', 'b', 'c'));
        $this->assertContains($generator->generate(), array('a', 'b', 'c'));
    }
}
