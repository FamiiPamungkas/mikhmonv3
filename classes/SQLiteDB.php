<?php

namespace classes;

use PDO;
use PDOStatement;

class SQLiteDB
{
    /**
     * @var $instance SQLiteDB
     */
    static private $instance = null;

    public $db;
    /**
     * @var $stmt PDOStatement
     */
    public $stmt;

    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    private function __construct()
    {
        $databasePath = __DIR__ . '/../database/mikhmon.db';
        $this->db = new PDO('sqlite:' . $databasePath);

        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Singleton
    |--------------------------------------------------------------------------
    */

    public static function getInstance(): ?SQLiteDB
    {
        if (self::$instance === null) {
            self::$instance = new SQLiteDB();
        }

        return self::$instance;
    }


    /*
    |--------------------------------------------------------------------------
    | Basic Query
    |--------------------------------------------------------------------------
    */

    public function query($sql, $params = []): SQLiteDB
    {
        $this->stmt = $this->db->prepare($sql);
        $this->stmt->execute($params);
        return $this;
    }

    public function exec($sql): bool
    {
        return $this->db->exec($sql) !== false;
    }

    /*
    |--------------------------------------------------------------------------
    | Fetch Helpers
    |--------------------------------------------------------------------------
    */

    public function fetch()
    {
        $result = $this->stmt->fetch();
        return $result ?: null;
    }

    public function fetchAll(): array
    {
        return $this->stmt->fetchAll();
    }

    public function fetchColumn(): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function fetchAllClass($className): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_CLASS, $className);
    }

    public function fetchClass($className)
    {
        $list = $this->stmt->fetchAll(PDO::FETCH_CLASS, $className);
        foreach ($list as $o){
            return $o;
        }
        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Insert
    |--------------------------------------------------------------------------
    */

    public function insert($table, $data): bool
    {
        $columns = array_keys($data);

        $placeholders = [];
        foreach ($columns as $column) {
            $placeholders[] = ":$column";
        }

        $sql = sprintf("INSERT INTO %s (%s) VALUES (%s)", $table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );

        return $this->query($sql, $data)->rowCount() > 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update($table, $data, $where, $whereParams = []): bool
    {

        $set = [];

        foreach ($data as $column => $value) {
            $set[] = "{$column} = :{$column}";
        }

        $sql = sprintf(
            "UPDATE %s SET %s WHERE %s",
            $table,
            implode(', ', $set),
            $where
        );

        $params = array_merge(
            $data,
            $whereParams
        );

        return $this->query($sql, $params)->rowCount() > 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function delete(
        $table,
        $where,
        $params = []
    ): bool
    {

        $sql = sprintf(
            "DELETE FROM %s WHERE %s",
            $table,
            $where
        );

        return $this->query(
                $sql,
                $params
            )->rowCount() > 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Exists
    |--------------------------------------------------------------------------
    */

    public function exists($table, $where, $params = []): bool
    {
        if (is_array($where)) {
            $where = implode(" AND ", $where);
        }

        $sql = sprintf("SELECT 1 FROM %s WHERE %s LIMIT 1", $table, $where);
        return (bool)$this->fetchColumn($sql, $params);
    }

    /*
    |--------------------------------------------------------------------------
    | Transaction
    |--------------------------------------------------------------------------
    */

    public function beginTransaction(): bool
    {
        return $this->db->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->db->commit();
    }

    public function rollback(): bool
    {
        return $this->db->rollBack();
    }

    /*
    |--------------------------------------------------------------------------
    | Utility
    |--------------------------------------------------------------------------
    */

    public function lastInsertId(): string
    {
        return $this->db->lastInsertId();
    }

    public function getPDO(): PDO
    {
        return $this->db;
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }
}