<?php

namespace app\db\drivers;
use PDO;
use PDOException;
use PDOStatement;
use WebApp;

class Mysql {
    /**
     * Stored connection.
     */
    protected $_connection = null;

    /**
     * Make a connection.
     */
    public function __construct(string $dsn, string $username, string $password)
    {
        $this->_connection = new \PDO($dsn, $username, $password);
    }

    /**
     * Executes query and return all rows.
     * @param string $sql DB query.
     * @param array $fields fields to apply query.
     * 
     * @return PDOStatement Statement containing query result.
     */
    public function query(string $sql, array $fields = [])
    {
        $stmt = $this->_connection->prepare($sql);

        foreach ($fields as $name => $field) {
            if (is_array($field)) {
                $stmt->bindValue($name, $field[0], $field[1]);
            } else {
                $stmt->bindValue($name, $field);
            }
        }

        $stmt->execute();

        return $stmt;
    }

    /**
     * Executes insert query and return its id.
     * @param string $sql DB query.
     * @param array $fields fields to applied query.
     * 
     * @return int last insert id.
     */
    public function insert($sql, $fields)
    {
        $stmt = $this->_connection->prepare($sql);
        $stmt->execute($fields);
        return $this->_connection->lastInsertId();
    }

    /**
     * Use this to update rows.
     * @param string $sql DB query.
     * @param array $fields fields to applied query.
     */
    public function update($sql, $fields = [])
    {
        $stmt = $this->_connection->prepare($sql);
        $stmt->execute($fields);
    }

    /**
     * Executes an unprepared query.
     * @param string $sql
     * @return false|int
     */
    public function exec(string $sql) {
        return $this->_connection->exec($sql);
    }

    public function transaction(callable $fn): mixed {
        $this->_connection->beginTransaction();
        try{
            $result = call_user_func($fn, $this);
            $this->_connection->commit();
            return $result;
        } catch(PDOException $e){
            $this->_connection->rollBack();
            throw $e;
        }
    }
}