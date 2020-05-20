<?php
    define("ROOT_PATH", dirname(__FILE__));
    define("SITE_URL", "http://test.loc");
    define("API_URL", "http://api.test.loc/");
    define("PRODUCT_DEFAULT_IMAGE", "/images/unnamed.png");
    define("DB_USER", "db_new");
    define("DB_PASS", "1111");
    define("DB_NAME", "test");
    define("SALT", "4rdhfewidcy!@ksdbiq7");

    $dsn = "mysql:host=localhost;port=3306;dbname=".DB_NAME.";charset=utf8";
    $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $opt);
    session_start();