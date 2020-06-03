<?php

namespace Shop\Models;

class CartProduct extends Product
{
    protected $id;
    public $productId;
    public $selectedQuantity;
    public $cartId;


    public function __construct($product, $selectedQuantity, $cartId)
    {
        parent::__construct($product->getId(), $product->name, $product->price, $product->quantity, $product->categoryId, $product->image);
        $this->selectedQuantity = $selectedQuantity;
        $this->cartId = $cartId;
        $this->productId = $product->getId();
    }

    public function save()
    {
        if(!empty($this->id)){
            $this->update();
            return;
        }
        $stmt = Database::getInstance()->prepare("
                INSERT INTO `cart_products` (
                    `product_id`,
                    `cart_id`,
                    `count`
                )
                VALUES
                (
                    :product_id,
                    :cart_id,
                    :count
                )"
        );
        $stmt->execute(
            [
                "product_id" => $this->productId,
                "cart_id" => $this->cartId,
                "count" => $this->selectedQuantity
            ]
        );
        $this->id = Database::getInstance()->lastInsertId();
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    private function update()
    {
        if($this->selectedQuantity == 0){
            $this->delete();
        } else{
            $stmt = Database::getInstance()->prepare("
                UPDATE
                    `cart_products`
                SET
                    `count` = :count
                WHERE
                    `product_id` = :product_id
                AND `cart_id` = :cart_id"
            );
            $stmt->execute(["product_id" => $this->productId, "cart_id" => $this->cartId, "count" => $this->selectedQuantity]);
        }
    }

    private function delete()
    {
        $stmt = Database::getInstance()->prepare("
            UPDATE
                `cart_products`
            SET
                `is_deleted` = 1
            WHERE
                `id` = :id"
        );
        $stmt->execute(["id" => $this->id]);
    }
}