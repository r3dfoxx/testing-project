<?php
    require_once dirname(__FILE__). "/config.php";
    require_once dirname(__FILE__). "/functions.php";

    //$products = json_decode(file_get_contents(ROOT_PATH . "/products.json"), true);
    if (!empty($_POST['categories'])) {
        $products = getProductsByCategories($pdo, $_POST['categories']);
    } else {
        $products = getAllProducts($pdo);
    }
    $categories = getAllCategories($pdo);
    if (!empty($_POST['products'])) {
        foreach ($_POST['products'] as $product) {
            $_SESSION['products'][] = ["id" => $product, "quantity" => 1];
            $userId = $_SESSION['user_id'] ?? 0;
            if (empty($_SESSION['cart_id'])) {
                $cartId = createCart($pdo, $userId);
                $_SESSION['cart_id'] = $cartId;
            } else{
                $cartId = $_SESSION['cart_id'];
            }
            addProductToCart($pdo, $product, $cartId, 1);
        }
        //update total price

        //redirect to cart
        header("Location: /cart.php");
    }
    require_once dirname(__FILE__) . "/views/products.php";
?>
