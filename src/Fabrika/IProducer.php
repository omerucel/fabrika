<?php

namespace Fabrika;

/**
 * Class IProducer
 * @package Fabrika
 */
interface IProducer
{
    /**
     * @param string|Definition $definition
     * @return Definition
     */
    public function define($definition);

    /**
     * @param $tableName
     * @return bool
     */
    public function hasDefinition($tableName);

    /**
     * @param $tableName
     * @return Definition|null
     */
    public function getDefinition($tableName);

    /**
     * @param $tableName
     * @param array $overrideFields
     * @return mixed
     */
    public function build($tableName, array $overrideFields = null);

    /**
     * @param $tableName
     * @param array $overrideFields
     * @return mixed
     */
    public function create($tableName, array $overrideFields = null);

    /**
     * @param string|null $tableName
     * @return mixed
     */
    public function flush($tableName);

    /**
     * @param $tableName
     * @return mixed
     */
    public function onFlush($tableName);

    /**
     * @param $tableName
     * @param int $step
     * @return mixed
     */
    public function onIncrementCounters($tableName, $step = 1);
}
