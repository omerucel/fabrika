<?php

namespace Fabrika\Producer;

use Fabrika\Generator\StringSequence;

class ArrayProducerTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceOf()
    {
        $producer = new ArrayProducer();
        $this->assertInstanceOf('Fabrika\IProducer', $producer);
    }

    public function testBuild()
    {
        $producer = new ArrayProducer();
        $producer->setDefinition(
            array(
                'name' => 'name'
            )
        );
        $array = $producer->build();
        $this->assertArrayHasKey('name', $array);
    }

    public function testOverrideFields()
    {
        $producer = new ArrayProducer();
        $producer->setDefinition(
            array(
                'name' => 'name'
            )
        );

        $array = $producer->build(
            array(
                'name' => 'overrided value'
            )
        );
        $this->assertEquals('overrided value', $array['name']);
    }

    public function testNewField()
    {
        $producer = new ArrayProducer();
        $producer->setDefinition(
            array(
                'name' => 'name'
            )
        );

        $array = $producer->build(
            array(
                'surname' => 'new field'
            )
        );
        $this->assertArrayHasKey('surname', $array);
    }

    public function testGetStorageCount()
    {
        $producer = new ArrayProducer();
        $producer->setDefinition(
            array(
                'name' => 'name'
            )
        );
        $producer->build();
        $producer->build();
        $producer->build();
        $this->assertEquals(3, $producer->getStorageCount());
    }

    public function testSupporGenerator()
    {
        $producer = new ArrayProducer();
        $producer->setDefinition(
            array(
                'name' => new StringSequence('name{n}')
            )
        );

        $array1 = $producer->build();
        $array2 = $producer->build();

        $this->assertArrayHasKey('name', $array1);
        $this->assertArrayHasKey('name', $array2);
        $this->assertEquals('name1', $array1['name']);
        $this->assertEquals('name2', $array2['name']);
    }
}
