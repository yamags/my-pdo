<?php

/**
 * Created by PhpStorm.
 * User: yama_gs
 * Date: 25.12.2015
 * Time: 17:10
 */
namespace DataBase\Query;

use DataBase\DBConnectionInterface;
use PDO;
class DBQuery implements DBQueryInterface
{
    /** @var DBConnectionInterface */
    private $db;
    /**
     * @var float
     */
    private $lastQueryTime;


    /**
     * Create new instance DBQuery.
     *
     * @param DBConnectionInterface $DBConnection
     */
    public function __construct(DBConnectionInterface $DBConnection)
    {
        $this->db = $DBConnection;
    }

    /**
     * Returns the DBConnection instance.
     *
     * @return DBConnectionInterface
     */
    public function getDBConnection()
    {
        return $this->db;
    }

    /**
     * Change DBConnection.
     *
     * @param DBConnectionInterface $DBConnection
     *
     * @return void
     */
    public function setDBConnection(DBConnectionInterface $DBConnection)
    {
        $this->db = $DBConnection;
    }

    /**
     * Executes the SQL statement and returns query result.
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return mixed if successful, returns a PDOStatement on error false
     */
    public function query($query, $params = null)
    {
        $pdo = $this->db->getPdoInstance();
        $pdoStatement = $pdo->prepare($query);
        $this->lastQueryTime=microtime(true);
        $result=$pdoStatement->execute($params);
        $this->lastQueryTime=microtime(true)-$this->lastQueryTime;
        if ($result) {
            return $pdoStatement;
        } else {
            return false;
        }


    }

    /**
     * Executes the SQL statement and returns all rows of a result set as an associative array
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return array
     */
    public function queryAll($query, array $params = null)
    {
        $pdoStatement = $this->query($query, $params);
        return ($pdoStatement ? $pdoStatement->fetchAll(PDO::FETCH_ASSOC) : false);
    }

    /**
     * Executes the SQL statement returns the first row of the query result
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return array
     */
    public function queryRow($query, array $params = null)
    {
        $pdoStatement = $this->query($query, $params);
        return ($pdoStatement ? $pdoStatement->fetch(PDO::FETCH_ASSOC) : false);
    }

    /**
     * Executes the SQL statement and returns the first column of the query result.
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return array
     */
    public function queryColumn($query, array $params = null)
    {
        $pdoStatement = $this->query($query, $params);
        return ($pdoStatement ? $pdoStatement->fetchAll(PDO::FETCH_COLUMN) : false);
    }


    /**
     * Executes the SQL statement and returns the first field of the first row of the result.
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return mixed  column value
     */
    public function queryScalar($query, array $params = null)
    {
        $pdoStatement = $this->query($query, $params);
        return ($pdoStatement ? $pdoStatement->fetch(PDO::FETCH_COLUMN) : false);
    }

    /**
     * Executes the SQL statement.
     * This method is meant only for executing non-query SQL statement.
     * No result set will be returned.
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return integer number of rows affected by the execution.
     */
    public function execute($query, array $params = null)
    {
        $pdoStatement = $this->query($query, $params);
        return ($pdoStatement ? $pdoStatement->rowCount () : false);
    }


    /**
     * Returns the last query execution time in seconds
     *
     * @return float query time in seconds
     */
    public function getLastQueryTime()
    {
        return $this->lastQueryTime;
    }

}