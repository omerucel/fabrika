<?php

namespace Fabrika;

abstract class ProducerAbstract implements IProducer
{
    /**
     * @var array
     */
    protected $definitions = array();

    /**
     * @param string|Definition $definition
     * @return Definition
     */
    public function define($definition)
    {
        if (!$definition instanceof Definition) {
            $definition = new Definition(strval($definition));
        }

        $this->definitions[$definition->getTableName()] = $definition;
        return $definition;
    }

    /**
     * @param $tableName
     * @return bool
     */
    public function hasDefinition($tableName)
    {
        return isset($this->definitions[$tableName]);
    }

    /**
     * @param $tableName
     * @return Definition|null
     */
    public function getDefinition($tableName)
    {
        return $this->definitions[$tableName];
    }

    /**
     * @param $tableName
     * @param array $overrideFields
     * @return mixed
     */
    public function build($tableName, array $overrideFields = null)
    {
        $tableFields = $this->getDefinition($tableName)->getFields();
        $objectClassName = $this->getDefinition($tableName)->getObjectClassName();

        $fields = $overrideFields == null ? $tableFields : array_merge($tableFields, $overrideFields);
        return $this->buildObject($fields, $objectClassName);
    }

    /**
     * @param array $fields
     * @param string $objectClassName
     * @return mixed
     */
    public function buildObject(array $fields = array(), $objectClassName = '\stdClass')
    {
        $object = new $objectClassName();
        foreach ($fields as $fieldName => $value) {
            if ($value instanceof IGenerator) {
                $value = $value->generate();
            }

            $object->{$fieldName} = $value;
        }

        return $object;
    }

    /**
     * @param $tableName
     * @return mixed
     */
    public function onFlush($tableName)
    {
        $this->getDefinition($tableName)->invokeOnFlush();
    }

    /**
     * @param $tableName
     * @param int $step
     * @return mixed
     */
    public function onIncrementCounters($tableName, $step = 1)
    {
        $this->getDefinition($tableName)->invokeOnIncrementCounters($step);
    }
}
