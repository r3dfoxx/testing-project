<?php
namespace Shop\Models\Traits;

use Shop\Models\Database;

trait FindRecord
{
    public function findOne($id, $dbTable)
    {
        $stmt = Database::getInstance()->prepare("SELECT * FROM `" . $dbTable . "` WHERE id = :id");
        $stmt->execute(["id" => $id]);
        $item = $stmt->fetch();
        return $item;
    }

    public function findAllRecord()
    {
        $stmt = Database::getInstance()->prepare("SELECT * FROM `" . self::$dbTable . "`");
        $stmt->execute();
        $items = $stmt->fetchAll();
        return $items;
    }
}