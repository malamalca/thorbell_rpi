<?php
namespace App\Model\Table;

use App\DB;
use App\Model\Entity\Device;

class Table
{
    public $entityName;
    public $tableName;
    public $fieldList = [];
    public $idField = 'id';

    /**
     * Create new entity from data
     *
     * @param array $data Data to fill entity with
     * @return object
     */
    public function newEntity($data = [])
    {
        $entity = new $this->entityName();
        foreach ($this->fieldList as $field) {
            if (isset($data[$field])) {
                $entity->{$field} = $data[$field];
            }
        }

        return $entity;
    }

    /**
     * Fetch entity by id
     *
     * @param string $id Entity id
     * @return object|null
     */
    public function get($id)
    {
        $pdo = DB::getInstance()->connect();

        $stmt = $pdo->prepare('SELECT * FROM ' . $this->tableName . ' WHERE ' . $this->idField . '=:' . $this->idField);

        $stmt->bindValue(':' . $this->idField, $id, \PDO::PARAM_STR);

        if ($stmt->execute()) {
            if ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                return self::newEntity($row);
            }
        }

        return null;
    }

    /**
     * Save entity
     *
     * @param object $entity Entity object
     * @return bool
     */
    public function save($entity)
    {
        $pdo = DB::getInstance()->connect();

        $exists = $this->get($entity->{$this->idField}) !== null;

        try {
            if ($exists) {
                // UPDATE statement
                $fieldNameValue = '';
                foreach ($this->fieldList as $field) {
                    if ($field != $this->idField) {
                        if ($fieldNameValue != '') {
                            $fieldNameValue .= ', ';
                        }

                        $fieldNameValue .= $field . '=:' . $field;
                    }
                }

                $sql = 'UPDATE ' . $this->tableName . ' SET ' . $fieldNameValue . ' WHERE ' . $this->idField . '=:' . $this->idField;
            } else {
                // INSERT statement
                $fieldList = implode(', ', $this->fieldList);
                $valuesList = ':' . implode(', :', $this->fieldList);

                $sql = 'INSERT INTO ' . $this->tableName . ' (' . $fieldList . ') VALUES (' . $valuesList . ')';
            }

            // prepare parameter values
            $stmt = $pdo->prepare($sql);
            foreach ($this->fieldList as $field) {
                $stmt->bindValue(':' . $field, $entity->{$field});
            }

            // execute query
            $result = (bool)$stmt->execute();

            if (!$result) {
                $this->lastError = $stmt->errorInfo();
            }

            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Delete entity
     *
     * @param object $entity Entity object
     * @return bool
     */
    public function delete($entity)
    {
        $pdo = DB::getInstance()->connect();

        try {
            $stmt = $pdo->prepare('DELETE FROM ' . $this->tableName . ' WHERE ' . $this->idField . ' = :' . $this->idField);
            $stmt->bindValue(':' . $this->idField, $entity->{$this->idField}, \PDO::PARAM_STR);

            return (bool)$stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
