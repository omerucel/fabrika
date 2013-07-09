<?php

namespace Fabrika;

/**
 * Class IProducer
 * @package Fabrika
 */
interface IProducer
{
    /**
     * @param array $definition
     * @return mixed
     */
    public function setDefinition(array $definition);

    /**
     * @return array
     */
    public function getDefinition();

    /**
     * @param array $fields
     * @return mixed
     */
    public function build(array $fields = null);
}
