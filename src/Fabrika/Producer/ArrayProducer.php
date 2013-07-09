<?php

namespace Fabrika\Producer;

use Fabrika\IGenerator;
use Fabrika\IProducer;

class ArrayProducer implements IProducer
{
    /**
     * @var array
     */
    protected $definition = array();

    /**
     * @param array $definition
     * @return mixed|void
     */
    public function setDefinition(array $definition)
    {
        $this->definition = $definition;
    }

    /**
     * @return array
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * @param array $attributes
     * @return array
     */
    public function build(array $attributes = null)
    {
        $attributes = $attributes == null ? $this->getDefinition() : array_merge($this->getDefinition(), $attributes);

        $temp = array();
        foreach ($attributes as $field => $value) {
            if ($value instanceof IGenerator) {
                $value = $value->generate();
            }

            $temp[$field] = $value;
        }

        return $temp;
    }
}
