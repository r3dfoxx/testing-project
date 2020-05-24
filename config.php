<?php
    define("ROOT_PATH", dirname(__FILE__));
    define("SITE_URL", "http://test.loc");
    define("API_URL", "http://api.test.loc/");
    define("PRODUCT_DEFAULT_IMAGE", "/images/unnamed.png");
    define("DB_USER", "db_user");
    define("DB_PASS", "1111");
    define("DB_NAME", "test");
    define("SALT", "4rdhfewidcy!@ksdbiq7");

    require_once dirname(__FILE__). "/vendor/autoload.php";

    function autoload($className)
    {
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        require $fileName;
    }
    spl_autoload_register('autoload');

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $dsn = "mysql:host=localhost;port=3306;dbname=".DB_NAME.";charset=utf8";
    $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $opt);
    session_start();