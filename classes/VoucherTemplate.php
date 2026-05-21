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

    static function getByName($name): array
    {
        $db = SQLiteDB::getInstance();
        return $db->query(
            Sql::select(self::TABLE, "*", ["name=:name"]),
            ["name" => $name]
        )->fetchAllClass(self::getClass());
    }

    static function fetchAll(): array
    {
        $db = SQLiteDB::getInstance();
        $sql = Sql::select_all(self::TABLE);
        error_log($sql);
        return $db->query($sql)->fetchAllClass(self::getClass());
    }

    function saveOrUpdate(): bool
    {
        error_log("SAVE OR UPDATE ". print_r($this, true));
        $db = SQLiteDB::getInstance();
        $data = [
            "name" => $this->name,
            "header" => $this->header,
            "row" => $this->row,
            "footer" => $this->footer
        ];
        error_log("-> 1");

        if (!$this->name) {
            error_log("nama tidak boleh kosong!");
            return false;
        }
        error_log("-> 2");

        $exists = $db->exists(self::TABLE, ["name=:name", "id<>:id"], ["name" => $this->name, "id" => $this->id]);
        if ($exists) {
            error_log("template dengan nama $this->name sudah ada!");
            return false;
        }
        error_log("-> 3");

        try {
            if ($this->id > 0) {
                error_log("-> 4");
                $db->update(self::TABLE, $data, "id=:id", ["id" => $this->id]);
            } else {
                error_log("-> 5");
                $db->insert(self::TABLE, $data);
                $this->id = $db->lastInsertId();
            }
                error_log("-> 6");
            return true;
        } catch (Exception $e) {
                error_log("-> 7");
            error_log("Terjadi error : ", print_r($e, true));
            return false;
        }
    }


}