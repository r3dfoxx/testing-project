<?php

    function generateProducts()
    {
        $products[] = ["id" => 1, "name" => "SAMSUNG TV", "price" => 15000, "quantity" => 5];
        $products[] = ["id" => 2, "name" => "LG TV", "price" => 13000, "quantity" => 8];
        $products[] = ["id" => 3, "name" => "Iphone XS", "price" => 13000, "quantity" => 8];
        $products[] = ["id" => 4, "name" => "Xbox", "price" => 10000, "quantity" => 10];
        $products[] = ["id" => 5, "name" => "PHP Programming", "price" => 1000, "quantity" => 10];
        file_put_contents("products.json", json_encode($products));
    }
