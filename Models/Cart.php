<?php

namespace Models;

use Models\Interfaces\SaveData;

class Cart implements SaveData
{
    protected $id;
    public $userId;
    public $totalPrice;
    public $cartProducts = [];

    public function __construct($id = null, $userId = null)
    {
        $this->id = $id ?? null;
        $this->userId = $userId ?? 0;
        if( !empty($this->id) ){
            $this->getCartProducts();
        }
    }

    public function getCartProducts()
    {
        $stmt = Database::getInstance()->prepare("
            SELECT
                `products`.`id` as product_id,
                `products`.`name`,
                `products`.`price`,
                `products`.`image`,
                `cart_products`.`count` as selected_quantity,
                `products`.`quantity`,
                `cart_products`.`id`
            FROM
                `products`
            INNER JOIN `cart_products` ON `cart_products`.`product_id` = `products`.`id`
            WHERE
                `cart_products`.`cart_id` = :cart_id
            AND `cart_products`.`is_deleted` <> 1"
        );
        $stmt->execute(["cart_id" => $this->id]);
        $items = $stmt->fetchAll();
        $this->cartProducts = [];
        foreach ($items as $item) {
            $product = new CartProduct(
                Product::find($item['product_id']),
                $item['selected_quantity'],
                $this->id
            );
            $product->setId($item['id']);
            $this->cartProducts[] = $product;
        }
        return $this->cartProducts;
    }

    public function save()
    {
        $stmt = Database::getInstance()->prepare("
            INSERT INTO `cart` (
                `user_id`,
                `total_price`
            )
            VALUES
            (
                :user_id,
                :total_price
            )"
        );
        $stmt->execute([
           "user_id" => $this->userId,
           "total_price" => 0
        ]);
        return Database::getInstance()->lastInsertId();
    }

    public function getTotalPrice()
    {
        $totalPrice = 0;
        if(empty($this->cartProducts)){
            return $totalPrice;
        }

        foreach($this->cartProducts as $product){
            $totalPrice += $product->selectedQuantity * $product->price;
        }
        return $totalPrice;
    }

    public function delete()
    {
        $stmt = Database::getInstance()->prepare("
             FROM `cart_products`
            WHERE id = :id"
        );
        $stmt = Database::getInstance()->prepare("
            UPDATE
                `cart_products`
            SET
                `is_deleted` = 1
            WHERE
                `cart_id` = :cart_id"
        );
        $stmt->execute(["cart_id" => $this->id]);
    }

}