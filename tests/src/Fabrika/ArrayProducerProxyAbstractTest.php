<?php

namespace Fabrika;

use Fabrika\Fake\UserProducer;

class ArrayProducerProxyAbstractTest extends \PHPUnit_Framework_TestCase
{
    public function testBuild()
    {
        $array1 = UserProducer::build();
        $array2 = UserProducer::build();

        $this->assertArrayHasKey('name', $array1);
        $this->assertEquals('name1', $array1['name']);
        $this->assertArrayHasKey('name', $array2);
        $this->assertEquals('name2', $array2['name']);
    }
}
