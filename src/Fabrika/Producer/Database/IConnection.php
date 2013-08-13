<?php

namespace Fabrika\Producer\Database;

interface IConnection
{
    /**
     * @param $tableName
     * @param array $data
     * @return int
     */
    public function insert($tableName, array $data);

    /**
     * @param $tableName
     * @return int
     */
    public function flush($tableName);
}
