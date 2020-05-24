<?php
    require_once dirname(__FILE__). "/config.php"; // сonnect config file
    require_once dirname(__FILE__). "/functions.php"; // connect function file

    use Models\User; // connect  Namespaces /Models/class User  and other
    use Models\Cart;
    use Models\CartProduct;
    use Models\Product;

    $users = User::findAll(); //
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
                $cart = new Cart(null, $userId);
                $cartId = $cart->save();
                $_SESSION['cart_id'] = $cartId;
            } else{
                $cartId = $_SESSION['cart_id'];
                $cart = new Cart($cartId, $userId);
            }
            $isExist = 0;
            if(!empty($cart->cartProducts)){
                foreach($cart->cartProducts as $cproduct){
                    if($cproduct->productId == $product){
                        $isExist = 1;
                        break;
                    }
                }
            }
            if(!$isExist) {
                $product = new CartProduct(Product::find($product), 1, $cartId);
                $product->save();
            }
        }
        //update total price

        //redirect to cart
        header("Location: /cart.php"); //connect file cart
    }
    require_once dirname(__FILE__) . "/views/products.php"; //connect product file
?>
