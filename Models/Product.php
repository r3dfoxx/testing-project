<?php

namespace Models;

use Models\Interfaces\SaveData;

class Product implements SaveData
{
    use Traits\FindRecord;

    private $id;
    public $name = '';
    public $price;
    public $quantity;
    public $categoryId;
    public $image;
    private static $dbTable = 'products';

    public function __construct($id = null, $name = null, $price = null, $quantity = null, $categoryId = null, $image = null)
    {
        $this->id = $id ?? null;
        $this->name = $name ?? null;
        $this->price = $price ?? null;
        $this->quantity = $quantity ?? null;
        $this->categoryId = $categoryId ?? null;
        $this->image = $image ?? "";
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function save()
    {
        $stmt = Database::getInstance()->prepare(
            "
            INSERT INTO `products` (
                `name`,
                `price`,
                `quantity`,
                `category_id`,
                `image` 
            )
            VALUES
                (
                    :name,
                    :price,
                    :quantity,
                    :category_id,
                    :image
                )"
        );
        $stmt->execute(
            [
                "name" => $this->name,
                "price" => $this->price,
                "quantity" => $this->quantity,
                "category_id" => $this->category_id,
                "image" => $this->image
            ]
        );
        $this->id = Database::getInstance()->lastInsertId();
        return $this->id;
    }

    public static function find($id)
    {
        $item = self::findOne($id, self::$dbTable);
        $product = new Product(
            $item['id'],
            $item['name'],
            $item['price'],
            $item['quantity'],
            $item['category_id'],
            $item['image']
        );
        return $product;
    }

    public static function findAll()
    {
        $items = self::findAllRecord(self::$dbTable);
        $products = [];
        foreach ($items as $item) {
            $product = new Product(
                $item['id'],
                $item['name'],
                $item['price'],
                $item['quantity'],
                $item['category_id'],
                $item['image']
            );
            $products[] = $product;
        }
        return $products;
    }
}