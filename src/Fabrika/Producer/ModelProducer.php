<?php

namespace Fabrika\Producer;

use Fabrika\IGeneratorOnFlush;

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
     * @var array
     */
    protected $excludedFields = array();

    /**
     * @param \PDO $pdo
     * @param string $tableName
     * @param string $modelClass
     */
    public function __construct(\PDO $pdo, $tableName = '', $modelClass = '')
    {
        $this->pdo = $pdo;
        $this->tableName = $tableName;
        $this->modelClass = $modelClass;
    }

    /**
     * @param array $fields
     */
    public function setExcludedFields(array $fields)
    {
        $this->excludedFields = $fields;
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
        $tempAttributes = get_object_vars($class);
        $attributes = array();
        foreach ($tempAttributes as $key => $value) {
            if (!in_array($key, $this->excludedFields)) {
                $attributes[$key] = $value;
            }
        }

        $attributeKeys = implode(', ', array_keys($attributes));
        $attributeValues = substr(str_repeat('?,', count($attributes)), 0, -1);
        $sql = sprintf('INSERT INTO %s(%s) VALUES(%s)', $this->tableName, $attributeKeys, $attributeValues);
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
        $sql = 'DELETE FROM ' . $this->tableName . ' WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array($id));
        return $stmt->rowCount() == 1;
    }

    /**
     * @return int
     */
    public function flush()
    {
        foreach ($this->getDefinition() as $key => $value) {
            if ($value instanceof IGeneratorOnFlush) {
                $value->onFlush();
            }
        }

        $sql = 'SELECT COUNT(*) AS count FROM sqlite_master WHERE type="table" AND name=?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array($this->tableName));
        if ($stmt->fetchColumn(0) == 0) {
            return 0;
        }

        $sql = 'DELETE FROM ' . $this->tableName;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
