<?php

    function getAllProducts($db)
    {
        $stmt = $db->prepare("SELECT * FROM `products`");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function getProductsByCategory($db, $categoryId)
    {
        $stmt = $db->prepare("SELECT * FROM `products` WHERE category_id = :category_id");
        $stmt->execute(["category_id" => $categoryId]);
        return $stmt->fetchAll();
    }

    function getProductsByCategories($db, $categories)
    {
        $in  = str_repeat('?,', count($categories) - 1) . '?';
        $stmt = $db->prepare("SELECT * FROM `products` WHERE category_id in (" . $in .")");
        $stmt->execute($categories);
        return $stmt->fetchAll();
    }

    function getProductFromCart($db, $cartId, $productId)
    {
        if (empty($cartId) || empty($productId)) {
            return false;
        }
        $stmt = $db->prepare("
            SELECT
                `id`
            FROM
                `cart_products`
            WHERE
                `cart_id` = :cart_id
            AND `product_id` = :product_id
            AND `is_deleted` = 0
        ");
        $stmt->execute(["cart_id" => $cartId, "product_id" => $productId]);
        return $stmt->fetch(PDO::FETCH_COLUMN);
    }

    function addProductToCart($db, $productId, $cartId, $quantity)
    {
        if (!getProductFromCart($db, $cartId, $productId)) {
            $stmt = $db->prepare("
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
                    "product_id" => $productId,
                    "cart_id" => $cartId,
                    "count" => $quantity
                ]
            );
            return true;
        }
    }

    function createCart($db, $userId, $totalPrice = 0)
    {
        $stmt = $db->prepare("
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
            "user_id" => $userId,
            "total_price" => $totalPrice
        ]);
        return $db->lastInsertId();
    }

    function getProductsByFilters($db, $categories)
    {
        $filter = '';
        $param = [];
        for ($i = 0; $i < count($categories); $i++) {
            $filter .= ' OR category_id = :category_' . $i;
            $param['category_' . $i] = $categories[$i];
        }
        $filter = ltrim($filter, " OR");
        $stmt = $db->prepare("SELECT * FROM `products` WHERE " . $filter);
        $stmt->execute($param);
        return $stmt->fetchAll();
    }

    function getAllCategories($db)
    {
        $stmt = $db->prepare("SELECT * FROM `category`");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function generateProducts()
    {
        $products[] = ["id" => 1, "name" => "SAMSUNG TV", "price" => 15000, "quantity" => 5, "img" => "product1.jpg"];
        $products[] = ["id" => 2, "name" => "LG TV", "price" => 13000, "quantity" => 8, "img" => "product2.jpg"];
        $products[] = ["id" => 3, "name" => "Iphone XS", "price" => 13000, "quantity" => 8];
        $products[] = ["id" => 4, "name" => "Xbox", "price" => 10000, "quantity" => 10,  "img" => "product1.jpg"];
        $products[] = ["id" => 5, "name" => "PHP Programming", "price" => 1000, "quantity" => 2];
        file_put_contents("products.json", json_encode($products));
    }

    function getCartProducts($db, $cartId)
    {
        if (empty($db) || empty($cartId)) {
            return [];
        }
        $productsCart = [];
        $stmt = $db->prepare("
            SELECT
                `products`.`id`,
                `products`.`name`,
                `products`.`price`,
                `products`.`image`,
                `cart_products`.`count` as selected_quantity,
                `products`.`quantity`
            FROM
                `products`
            INNER JOIN `cart_products` ON `cart_products`.`product_id` = `products`.`id`
            WHERE
                `cart_products`.`cart_id` = :cart_id
            AND `cart_products`.`is_deleted` <> 1"
        );
        $stmt->execute(["cart_id" => $cartId]);
        return $stmt->fetchAll();
    }

    function updateCartProductQuantity($db, $cartId, $productId, $quantity)
    {
        if (empty($db) || empty($cartId) || empty($productId)) {
            return false;
        }
        if($quantity == 0){
            /*$stmt = $db->prepare("
                DELETE
                FROM
                    `cart_products`
                WHERE
                    `product_id` = :product_id
                AND `cart_id` = :cart_id"
            );*/
            $stmt = $db->prepare("
                UPDATE
                    `cart_products`
                SET
                    `is_deleted` = 1
                WHERE
                    `product_id` = :product_id
                AND `cart_id` = :cart_id"
            );
            $stmt->execute(["product_id" => $productId, "cart_id" => $cartId]);
        } else{
            $stmt = $db->prepare("
                UPDATE
                    `cart_products`
                SET
                    `count` = :count
                WHERE
                    `product_id` = :product_id
                AND `cart_id` = :cart_id"
            );
            $stmt->execute(["product_id" => $productId, "cart_id" => $cartId, "count" => $quantity]);
        }
    }

    function deleteAllCartProducts($db, $cartId)
    {
        if (empty($db) || empty($cartId)) {
            return false;
        }
        $stmt = $db->prepare("
                UPDATE
                    `cart_products`
                SET
                    `is_deleted` = 1
                WHERE
                    `cart_id` = :cart_id"
        );
        $stmt->execute(["cart_id" => $cartId]);
    }

    function registerUser($db, $userName, $email, $password)
    {
        if (empty($db) || empty($userName) || empty($email) || empty($password)) {
            return false;
        }
        $stmt = $db->prepare("
            INSERT INTO `users` (
                `user_name`,
                `email`,
                `password`
            )
            VALUES
                (
                    :user_name,
                    :email,
                    :password
                )"
        );
        $stmt->execute([
            "user_name" => $userName,
            "email" => $email,
            "password" => sha1($password . SALT)
        ]);
        return $db->lastInsertId();
    }

    function loginUser($db, $email, $password)
    {
        if (empty($email) || empty($password)) {
            return false;
        }
        $stmt = $db->prepare("
            SELECT
                `id`
            FROM
                `users`
            WHERE
                `email` = :email
            AND `password` = :password
            AND `status` = 'Active'
        ");
        $stmt->execute(["email" => $email, "password" => sha1($password . SALT)]);
        return $stmt->fetch(PDO::FETCH_COLUMN);
    }

    function getProductQuantity($products, $id)
    {
        foreach($products as $product){
            if($product['id'] == $id){
                return $product['quantity'];
            }
        }
    }

    function getTotalPrice($products)
    {
        $sum = 0;
        foreach($products as $product){
            $sum += $product['price'] * $product['selected_quantity'];
        }
        return $sum;
    }
