<?php
    require_once dirname(__FILE__). "/config.php";
    require_once dirname(__FILE__). "/functions.php";

    use Models\Cart;

    if (empty($_SESSION['products'])) {
        header("Location: index.php");
    }

    $userId = $_SESSION['user_id'] ?? 0;
    $cart = new Cart($_SESSION['cart_id'], $userId);
    if (!empty($_POST)) {
        if (!empty($_POST['reset'])) {
            unset($_SESSION['products']);
            $cart->delete();
            unset($_SESSION['cart_id']);
            unset($cart);
            header("Location: index.php");
            die();
        }
        unset($_POST['update']);
        foreach ($_POST as $k => $val){
            $id = explode("_", $k)[1];
            foreach($cart->cartProducts as $product){
                if($product->getId() == $id){
                    $product->selectedQuantity = $val;
                    $product->save();
                }
            }
            foreach($_SESSION['products'] as $key => &$product){
                if(!empty($product) && $product['id'] == $id){
                    if($val == 0){
                        array_splice($_SESSION['products'], $key, 1);
                    } else {
                        $product['quantity'] = $val;
                    }
                }
            } unset($product);
        }
        $cart->getCartProducts();
    }
    $products = $cart->cartProducts;
    $totalPrice = $cart->getTotalPrice();
    require_once dirname(__FILE__) . "/views/cart.php";
?>
