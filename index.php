<?php
    require_once dirname(__FILE__). "/config.php";
    require_once dirname(__FILE__). "/functions.php";


    $products = json_decode(file_get_contents(ROOT_PATH . "/products.json"), true);
    if (!empty($_POST['products'])) {
        $_SESSION['products'] = $_POST['products'];
        //header('Location: http.test.loc/cart.php/');
    }
    require_once dirname(__FILE__) . "/views/products.php";


?>
