<?php

namespace Shop\Models;

use Shop\Models\Interfaces\SaveData;

class Category implements SaveData
{
    use Traits\FindRecord;

    private $id;
    public $categoryName = '';
    private static $dbTable = 'category';

    public function __construct($id = null, $categoryName = null)
    {
        $this->id = $id ?? null;
        $this->categoryName = $categoryName ?? null;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function save()
    {
        $stmt = Database::getInstance()->prepare(
            "
            INSERT INTO `category` (
                `category_name`
            )
            VALUES
                (
                    :category_name
                )"
        );
        $stmt->execute(
            [
                "category_name" => $this->categoryName,
            ]
        );
        $this->id = Database::getInstance()->lastInsertId();
        return $this->id;
    }

    public static function find($id)
    {
        $item = self::findOne($id, self::$dbTable);
        $category = new Category(
            $item['id'],
            $item['category_name']
        );
        return $category;
    }

    public static function findAll()
    {
        $items = self::findAllRecord(self::$dbTable);
        $categories = [];
        foreach ($items as $item) {
            $category = new Category(
                $item['id'],
                $item['category_name'],
            );
            $categories[] = $category;
        }
        return $categories;
    }
}