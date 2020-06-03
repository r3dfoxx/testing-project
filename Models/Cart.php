<?php

namespace Shop\Models;

use Shop\Models\Interfaces\SaveData;

class Cart implements SaveData, \Iterator
{
    protected $id;
    public $userId;
    public $totalPrice;
    public $cartProducts = [];
    private static $cartProductIds = [];
    private $count = 0;
    private $index = 0;

    public function __construct($id = null, $userId = null)
    {
        $this->id = $id ?? null;
        $this->userId = $userId ?? 0;
        if( !empty($this->id) ){
            $this->getCartProducts();
        }
    }

    public function current()
    {
        return $this->cartProducts[$this->index];
    }

    public function next()
    {
        $this->index++;
    }

    public function rewind()
    {
        $this->index = 0;
    }

    public function key()
    {
        return $this->index;
    }

    public function valid()
    {
        return isset($this->cartProducts[$this->key()]);
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
            if (!in_array($product->getId(), self::$cartProductIds)) {
                self::$cartProductIds[] = $product->getId();
            }
        }
        return $this->cartProducts;
    }

    public static function getCartProductIds(int $cartId, int $userId) : array
    {
        if(empty(self::$cartProductIds)){
            $cart = new Cart($cartId, $userId);
        }
        return self::$cartProductIds;
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

    public function updateProductQuantity(int $productId, int $quantity)
    {
        foreach($this->cartProducts as $product){
            if($product->getId() == $productId){
                $product->selectedQuantity = $quantity;
                $product->save();
            }
        }
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