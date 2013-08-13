<?php

namespace Fabrika;

class FieldTest extends \PHPUnit_Framework_TestCase
{
    public function testGetName()
    {
        $field = new Field('id');
        $this->assertEquals('id', $field->getName());
    }

    public function testGetGenerator()
    {
        $mock = $this->getMock('Fabrika\IGenerator');
        $field = new Field('id', $mock);
        $this->assertInstanceOf('Fabrika\IGenerator', $field->getGenerator());
    }

    public function testDefaultGenerator()
    {
        $field = new Field('id');
        $this->assertInstanceOf('Fabrika\Generator\StringSequence', $field->getGenerator());
    }

    public function testDefaultGeneratorValue()
    {
        $field = new Field('id', 'username{n}');
        $this->assertInstanceOf('Fabrika\Generator\StringSequence', $field->getGenerator());
        $this->assertEquals('username{n}', $field->getGenerator()->getString());
    }
}
