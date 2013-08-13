<?php

namespace Fabrika\Producer;

use Fabrika\Producer\Database\IConnection;
use Fabrika\ProducerAbstract;

class DatabaseProducer extends ProducerAbstract
{
    /**
     * @var IConnection
     */
    protected $connection;

    /**
     * @param IConnection $connection
     */
    public function __construct(IConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return IConnection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param $tableName
     * @param array $overrideFields
     * @return mixed
     */
    public function create($tableName, array $overrideFields = null)
    {
        $object = $this->build($tableName, $overrideFields);
        $rowData = $this->getRowDataFromObject($object);
        $this->getConnection()->insert($tableName, $rowData);
        return $object;
    }

    /**
     * @param $object
     * @return array
     */
    public function getRowDataFromObject($object)
    {
        $columns = array();
        foreach ($object as $fieldName => $value)
        {
            $columns[$fieldName] = $value;
        }

        return $columns;
    }

    /**
     * @param string|null $tableName
     * @return bool
     */
    public function flush($tableName)
    {
        $status = $this->getConnection()->flush($tableName);
        $this->onFlush($tableName);
        return $status;
    }
}
