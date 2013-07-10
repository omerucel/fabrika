<?php

namespace Fabrika;

use Fabrika\Producer\ModelProducer;

abstract class ModelProducerProxyAbstract extends ArrayProducerProxyAbstract
{
    /**
     * @var \PDO
     */
    protected static $pdo;

    protected static $tableName = '';
    protected static $modelClass = '';
    protected static $excludedFields = array();

    /**
     * @param \PDO $pdo
     */
    public static function init(\PDO $pdo)
    {
        static::$pdo = $pdo;
    }

    /**
     * @param array $attributes
     * @return \stdClass
     */
    public static function create(array $attributes = null)
    {
        return static::getProducer()->create($attributes);
    }

    /**
     * @return int
     */
    public static function flush()
    {
        return static::getProducer()->flush();
    }

    /**
     * @param $id
     * @return bool
     */
    public static function delete($id)
    {
        return static::getProducer()->delete($id);
    }

    /**
     * @return ModelProducer
     */
    public static function getProducer()
    {
        $class = get_called_class();
        if (!isset(static::$producers[$class])) {
            $producer = new ModelProducer(static::$pdo, static::$tableName, static::$modelClass);
            $producer->setDefinition(static::getDefinition());
            $producer->setExcludedFields(static::$excludedFields);
            static::$producers[$class] = $producer;
        }

        return static::$producers[$class];
    }
}
