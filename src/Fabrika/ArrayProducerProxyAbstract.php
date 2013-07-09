<?php

namespace Fabrika;

use Fabrika\Producer\ArrayProducer;

abstract class ArrayProducerProxyAbstract implements IProducerProxy
{
    /**
     * @var array
     */
    protected static $producers = array();

    /**
     * @param array $attributes
     * @return mixed
     */
    public static function build(array $attributes = null)
    {
        return static::getProducer()->build($attributes);
    }

    /**
     * @return ArrayProducer
     */
    public static function getProducer()
    {
        $class = get_called_class();
        if (!isset(static::$producers[$class])) {
            $producer = new ArrayProducer();
            $producer->setDefinition(static::getDefinition());
            static::$producers[$class] = $producer;
        }

        return static::$producers[$class];
    }
}
