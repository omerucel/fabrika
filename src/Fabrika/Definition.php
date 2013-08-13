<?php

namespace Fabrika;

class Definition
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $objectClassName;

    /**
     * @var array
     */
    protected $fields = array();

    /**
     * @param $name
     * @param string $objectClassName
     */
    public function __construct($name, $objectClassName = '\stdClass')
    {
        $this->name = $name;
        $this->objectClassName = $objectClassName;
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getObjectClassName()
    {
        return $this->objectClassName;
    }

    /**
     * @param $fieldName
     * @param string $generator
     * @return $this
     */
    public function addField($fieldName, $generator = '')
    {
        $this->fields[$fieldName] = new Field($fieldName, $generator);
        return $this;
    }

    /**
     * @param $fieldName
     * @return bool
     */
    public function hasField($fieldName)
    {
        return isset($this->fields[$fieldName]);
    }

    /**
     * @param $fieldName
     * @return Field|null
     */
    public function getField($fieldName)
    {
        if (!$this->hasField($fieldName)) {
            return null;
        }

        return $this->fields[$fieldName];
    }

    /**
     * @return array
     */
    public function getFields()
    {
        $temp = array();
        /**
         * @var Field $field
         */
        foreach ($this->fields as $field) {
            $temp[$field->getName()] = $field->getGenerator();
        }

        return $temp;
    }

    /**
     *
     */
    public function invokeOnFlush()
    {
        /**
         * @var Field $field
         */
        foreach ($this->fields as $field) {
            $generator = $field->getGenerator();
            if ($generator instanceof IGeneratorOnFlush) {
                $generator->onFlush();
            }
        }
    }

    /**
     * @param int $step
     */
    public function invokeOnIncrementCounters($step = 1)
    {
        /**
         * @var Field $field
         */
        foreach ($this->fields as $field) {
            $generator = $field->getGenerator();
            if ($generator instanceof IGeneratorOnIncrementCounter) {
                $generator->onIncrementCounter($step);
            }
        }
    }
}
