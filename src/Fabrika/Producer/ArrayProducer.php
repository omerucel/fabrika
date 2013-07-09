<?php

namespace Fabrika\Producer;

use Fabrika\IProducer;

class ArrayProducer implements IProducer
{
    /**
     * @var array
     */
    protected $definition = array();

    /**
     * @var array
     */
    protected $storage = array();

    /**
     * @return int
     */
    public function getStorageCount()
    {
        return count($this->storage);
    }

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
        $temp = array();
        foreach ($this->getDefinition() as $field => $value) {
            // override fields
            if (isset($attributes[$field])) {
                $value = $attributes[$field];
                unset($attributes[$field]);
            }

            $temp[$field] = $value;
        }

        // new fields
        if (!is_null($attributes)) {
            foreach ($attributes as $field => $value) {
                $temp[$field] = $value;
            }
        }

        $this->storage[] = &$temp;
        return $temp;
    }
}
