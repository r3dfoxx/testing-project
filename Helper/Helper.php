<?php

namespace Shop\Helper;

use Shop\Models\Cart;

class Helper
{

    public function __construct()
    {
    }

    public function redirect(string $route)
    {
        header("Location: " . $route);
        die();
    }

    public function renderPartial($name, $data)
    {
        if (!empty($data)) {
            extract($data);
        }
        include ROOT_PATH . "/templates/partials/". $name . ".php";
    }

    public function checkProductInCart($productId)
    {
        if(empty($_SESSION['cart_id'])){
            return false;
        }
        $userId = $_SESSION['user_id'] ?? 0;
        return in_array($productId, Cart::getCartProductIds((int)$_SESSION['cart_id'], (int)$userId));
    }

}