<?php
namespace App\Core;

use \App\Core\DB;

class Table
{
    public $entityName;
    public $tableName;
    public $fieldList = [];
    public $idField = 'id';

    public $lastError;

    /**
     * Create new entity from data
     *
     * @param array $data Data to fill entity with
     * @return object
     */
    public function newEntity($data = [])
    {
        $entity = new $this->entityName();

        if (is_array($data)) {
            foreach ($this->fieldList as $field) {
                if (isset($data[$field])) {
                    $entity->{$field} = $data[$field];
                }
            }
        } else {
            $entity->{$this->idField} = $data;
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

        $sql = 'SELECT * FROM ' . $this->tableName . ' WHERE ' . $this->idField . '=:' . $this->idField;
        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':' . $this->idField, $id, \PDO::PARAM_STR);

        $result = $stmt->execute();
        if ($result) {
            if ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                return self::newEntity($row);
            }
        } else {
            $this->lastError = $stmt->errorInfo();
        }

        return null;
    }

    /**
     * Check if entity exists
     *
     * @param string $id Entity id
     * @return bool
     */
    public function exists($id)
    {
        $pdo = DB::getInstance()->connect();

        $sql = 'SELECT COUNT(id) AS cnt FROM ' . $this->tableName . ' WHERE ' . $this->idField . '=:' . $this->idField;
        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':' . $this->idField, $id, \PDO::PARAM_STR);

        $stmt->execute();
        $result = $stmt->fetchColumn();

        return ((int)$result > 0);
    }

    /**
     * Save entity
     *
     * @param object $entity Entity object
     * @param array $fieldFilter Specify fields to save. Save all if empty
     * @return bool
     */
    public function save($entity, $fieldFilter = [])
    {
        $pdo = DB::getInstance()->connect();

        $exists = $this->exists($entity->{$this->idField});

        try {
            if ($exists) {
                // UPDATE statement
                $fieldNameValue = '';
                foreach ($this->fieldList as $field) {
                    if (empty($fieldFilter) || in_array($field, $fieldFilter)) {
                        if ($field != $this->idField) {
                            if ($fieldNameValue != '') {
                                $fieldNameValue .= ', ';
                            }

                            $fieldNameValue .= $field . '=:' . $field;
                        }
                    }
                }

                $sql = 'UPDATE ' . $this->tableName . ' SET ' . $fieldNameValue . ' WHERE ' . $this->idField . '=:' . $this->idField;
            } else {
                // INSERT statement
                //$fieldList = implode(', ', $this->fieldList);
                //$valuesList = ':' . implode(', :', $this->fieldList);
                $fieldList = '';
                $valuesList = '';

                foreach ($this->fieldList as $field) {
                    if (empty($fieldFilter) || in_array($field, $fieldFilter)) {
                        if ($fieldList != '') {
                            $fieldList .= ', ';
                            $valuesList .= ', ';
                        }

                        $fieldList .= $field;
                        $valuesList .= ':' . $field;
                    }
                }

                $sql = 'INSERT INTO ' . $this->tableName . ' (' . $fieldList . ') VALUES (' . $valuesList . ')';
            }

            // prepare parameter values
            $stmt = $pdo->prepare($sql);
            foreach ($this->fieldList as $field) {
                if (empty($fieldFilter) || in_array($field, $fieldFilter)) {
                    $stmt->bindValue(':' . $field, $entity->{$field});
                }
            }

            // bind id field on update even if not specified in fieldlist
            if (!empty($fieldFilter) && !in_array($this->idField, $fieldFilter) && $exists) {
                $stmt->bindValue(':' . $this->idField, $entity->{$this->idField});
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
