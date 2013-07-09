<?php

namespace Fabrika\Producer;

class ArrayProducerTest extends \PHPUnit_Framework_TestCase
{
    public function testBuild()
    {
        $item = new ArrayProducer();
        $item->setDefinition(
            array(
                'name' => 'name'
            )
        );
        $array = $item->build();
        $this->assertArrayHasKey('name', $array);
    }

    public function testOverrideFields()
    {
        $item = new ArrayProducer();
        $item->setDefinition(
            array(
                'name' => 'name'
            )
        );

        $array = $item->build(
            array(
                'name' => 'overrided value'
            )
        );
        $this->assertEquals('overrided value', $array['name']);
    }

    public function testNewField()
    {
        $item = new ArrayProducer();
        $item->setDefinition(
            array(
                'name' => 'name'
            )
        );

        $array = $item->build(
            array(
                'surname' => 'new field'
            )
        );
        $this->assertArrayHasKey('surname', $array);
    }
}
