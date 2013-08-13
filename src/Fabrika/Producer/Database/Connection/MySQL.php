<?php

namespace Fabrika\Producer\Database\Connection;

use Fabrika\Producer\Database\IConnection;

class MySQL implements IConnection
{
    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param $tableName
     * @param array $data
     * @return int|mixed
     */
    public function insert($tableName, array $data)
    {
        $columnNames = implode(', ', array_keys($data));
        $columnValues = substr(str_repeat('?,', count($data)), 0, -1);

        $sql = sprintf('INSERT INTO %s(%s) VALUES(%s)', $tableName, $columnNames, $columnValues);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($data));
        $affectedRows = $stmt->rowCount();
        $stmt->closeCursor();

        return $affectedRows;
    }

    /**
     * @param $tableName
     * @return int
     */
    public function flush($tableName)
    {
        $sql = 'TRUNCATE TABLE ' . $tableName;
        $stmt = $this->pdo->prepare($sql);
        $status = $stmt->execute();
        $stmt->closeCursor();
        return $status;
    }
}
