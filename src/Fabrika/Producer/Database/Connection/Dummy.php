<?php

namespace Fabrika\Producer\Database\Connection;

use Fabrika\Producer\Database\IConnection;

class Dummy implements IConnection
{
    /**
     * @param $tableName
     * @param array $data
     * @return int
     */
    public function insert($tableName, array $data)
    {
    }

    /**
     * @param $tableName
     * @return int
     */
    public function flush($tableName)
    {
        return 0;
    }
}
