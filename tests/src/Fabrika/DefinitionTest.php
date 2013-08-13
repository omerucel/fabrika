<?php

namespace Fabrika;

use Fabrika\Generator\AutoIncrement;
use Fabrika\Generator\StringSequence;

class DefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetTableName()
    {
        $definition = new Definition('user');
        $this->assertEquals('user', $definition->getTableName());
    }

    public function testGetObjectClassName()
    {
        $definition = new Definition('user');
        $this->assertEquals('\stdClass', $definition->getObjectClassName());

        $definition = new Definition('user', 'Fabrika\Fake\Object');
        $this->assertEquals('Fabrika\Fake\Object', $definition->getObjectClassName());
    }

    public function testAddField()
    {
        $definition = new Definition('user');
        $definition->addField('id', new AutoIncrement())
            ->addField('username', new StringSequence('username{n}'));
        $this->assertTrue($definition->hasField('id'));
        $this->assertTrue($definition->hasField('username'));
        $this->assertFalse($definition->hasField('password'));
    }

    public function testGetField()
    {
        $definition = new Definition('user');
        $definition->addField('id', new AutoIncrement())
            ->addField('username', new StringSequence('username{n}'));
        $field = $definition->getField('id');
        $this->assertInstanceOf('Fabrika\Field', $field);

        $this->assertNull($definition->getField('password'));
    }

    public function testGetFields()
    {
        $definition = new Definition('user');
        $definition->addField('id', new AutoIncrement())
            ->addField('username', new StringSequence('username{n}'));
        $fields = $definition->getFields();
        $this->assertArrayHasKey('id', $fields);
        $this->assertInstanceOf('Fabrika\IGenerator', $fields['id']);
        $this->assertArrayHasKey('username', $fields);
        $this->assertInstanceOf('Fabrika\IGenerator', $fields['username']);
    }

    public function testInvokeOnFlush()
    {
        $definition = new Definition('user');
        $definition->addField('id', new AutoIncrement())
            ->addField('username', new StringSequence('username{n}'));

        $definition->getField('id')->getGenerator()->generate();
        $id = $definition->getField('id')->getGenerator()->generate();
        $this->assertEquals(2, $id);
        $definition->invokeOnFlush();
        $id = $definition->getField('id')->getGenerator()->generate();
        $this->assertEquals(1, $id);
    }

    public function testInvokeOnIncrementCounters()
    {
        $definition = new Definition('user');
        $definition->addField('id', new AutoIncrement())
            ->addField('username', new StringSequence('username{n}'));

        $definition->getField('id')->getGenerator()->generate();
        $id = $definition->getField('id')->getGenerator()->generate();
        $this->assertEquals(2, $id);
        $definition->invokeOnIncrementCounters(10);
        $id = $definition->getField('id')->getGenerator()->generate();
        $this->assertEquals(13, $id);
    }
}
