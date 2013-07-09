<?php

namespace Fabrika\Producer;

class ModelProducer extends ArrayProducer
{
    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var string
     */
    protected $modelClass;

    /**
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param string $tableName
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @param string $className
     */
    public function setModelClass($className)
    {
        $this->modelClass = $className;
    }

    /**
     * @return string
     */
    public function getModelClass()
    {
        return $this->modelClass;
    }

    /**
     * @param array $attributes
     * @return \stdClass
     */
    public function build(array $attributes = null)
    {
        $array = parent::build($attributes);
        $modelClass = $this->modelClass;
        $class = new $modelClass();
        foreach ($array as $key => $value) {
            $class->{$key} = $value;
        }

        return $class;
    }

    /**
     * @param array $attributes
     * @return \stdClass
     */
    public function create(array $attributes = null)
    {
        $class = $this->build($attributes);
        $attributes = get_object_vars($class);

        $sql = sprintf(
            'INSERT INTO %s(%s) VALUES(%s)',
            $this->getTableName(),
            implode(', ', array_keys($attributes)),
            substr(str_repeat('?,', count($attributes)), 0, -1)
        );
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($attributes));

        return $class;
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $sql = 'DELETE FROM ' . $this->getTableName() . ' WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array($id));
        return $stmt->rowCount() == 1;
    }

    /**
     * @return int
     */
    public function flush()
    {
        $sql = 'DELETE FROM ' . $this->getTableName();
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
