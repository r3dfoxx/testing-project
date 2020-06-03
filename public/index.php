<?php
    define("ROOT_PATH", dirname(__FILE__, 2));

    require_once ROOT_PATH. "/vendor/autoload.php";

    use \Shop\Router\Router;
    session_start();

    $dotenv = Dotenv\Dotenv::createImmutable(ROOT_PATH);
    $dotenv->load();

    $request = $_REQUEST ?? [];
    $path = $request['path'] ?? "/";
    unset($request['path']);
    $router = new Router();
    $router->process($path, $request);
?>
