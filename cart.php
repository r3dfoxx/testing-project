<?php
    require_once dirname(__FILE__). "/config.php";
    require_once dirname(__FILE__). "/functions.php";
    if (empty($_SESSION['products'])) {
        header("Location: index.php");
    }

    if (!empty($_POST)) {
        if (!empty($_POST['reset'])) {
            unset($_SESSION['products']);
            deleteAllCartProducts($pdo, $_SESSION['cart_id']);
            unset($_SESSION['cart_id']);
            header("Location: index.php");
            die();
        }
        unset($_POST['update']);
        foreach ($_POST as $k => $val){
            $id = explode("_", $k)[1];
            updateCartProductQuantity($pdo, $_SESSION['cart_id'], $id, $val);
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
    }
    $products = getCartProducts($pdo, $_SESSION['cart_id']);
    $totalPrice = getTotalPrice($products);
    require_once dirname(__FILE__) . "/views/cart.php";
?>
