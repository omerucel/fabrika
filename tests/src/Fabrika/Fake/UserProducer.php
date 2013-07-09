<?php

namespace Fabrika\Fake;

use Fabrika\ArrayProducerProxyAbstract;
use Fabrika\Generator\IntegerSequence;
use Fabrika\Generator\RandomItem;
use Fabrika\Generator\StringSequence;

class UserProducer extends ArrayProducerProxyAbstract
{
    /**
     * @return array
     */
    public static function getDefinition()
    {
        return array(
            'id' => new IntegerSequence(),
            'name' => new StringSequence('name{n}'),
            'email' => new StringSequence('email{n}@google.com'),
            'status' => new RandomItem(array(1, 0))
        );
    }
}
