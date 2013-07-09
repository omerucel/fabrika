<?php

namespace Fabrika;

interface IProducerProxy
{
    /**
     * @param array $attributes
     * @return mixed
     */
    public static function build(array $attributes = null);

    /**
     * @return array
     */
    public static function getDefinition();

    /**
     * @return IProducer
     */
    public static function getProducer();
}
