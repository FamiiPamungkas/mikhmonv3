<?php

namespace classes;

class Sql
{
    static function select($table, $field = "*", $where = [], $order = ""): string
    {
        if (is_array($field)) {
            $field = implode(", ", $field);
        }
        if (is_array($where)) {
            $where = implode(" AND ", $where);
        }

        $sql = "SELECT $field FROM $table ";
        if ($where) $sql .= "WHERE $where ";
        if ($order) $sql .= "ORDER BY $order";

        return $sql;
    }

    static function select_all($table, $where = []): string
    {
        return self::select($table, "*", $where);
    }
}