<?php

namespace classes;

use Exception;

class VoucherTemplate
{
    const TABLE = "voucher_templates";

    public $id = 0;
    public $name = "";
    public $header = "";
    public $row = "";
    public $footer = "";

    public function __construct($array = [])
    {
        if ($array && is_array($array)) {
            if (isset($array['id'])) $this->id = (int)$array["id"];
            if (isset($array['name'])) $this->name = $array["name"];
            if (isset($array['header'])) $this->header = $array["header"];
            if (isset($array['row'])) $this->row = $array["row"];
            if (isset($array['footer'])) $this->footer = $array["footer"];
        }
    }

    public static function getClass(): string
    {
        return __CLASS__;
    }

    static function getByName($name): ?VoucherTemplate
    {
        $db = SQLiteDB::getInstance();
        return $db->query(
            Sql::select(self::TABLE, "*", ["name=:name"]),
            ["name" => $name]
        )->fetchClass(self::getClass());
    }

    static function fetchAll(): array
    {
        $db = SQLiteDB::getInstance();
        $sql = Sql::select_all(self::TABLE);
        return $db->query($sql)->fetchAllClass(self::getClass());
    }

    static function fetchAllNames(): array
    {
        $db = SQLiteDB::getInstance();
        $sql = Sql::select(self::TABLE, "name", "", "id");
        return $db->query($sql)->fetchColumn();
    }

    function saveOrUpdate(): bool
    {
        $db = SQLiteDB::getInstance();
        $data = [
            "name" => $this->name,
            "header" => $this->header,
            "row" => $this->row,
            "footer" => $this->footer
        ];

        if (!$this->name) {
            error_log("nama tidak boleh kosong!");
            return false;
        }

        $exists = $db->exists(self::TABLE, ["name=:name", "id<>:id"], ["name" => $this->name, "id" => $this->id]);
        if ($exists) {
            error_log("template dengan nama $this->name sudah ada!");
            return false;
        }

        try {
            if ($this->id > 0) {
                $db->update(self::TABLE, $data, "id=:id", ["id" => $this->id]);
            } else {
                $db->insert(self::TABLE, $data);
                $this->id = $db->lastInsertId();
            }
            return true;
        } catch (Exception $e) {
            error_log("Terjadi error : ", print_r($e, true));
            return false;
        }
    }

    function delete(): bool
    {
        $db = SQLiteDB::getInstance();
        return $db->delete(self::TABLE, "id = :id", ["id" => $this->id]);
    }
}