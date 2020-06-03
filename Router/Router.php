<?php

namespace Shop\Router;

use Shop\Helper\Helper;

class Router
{
    private $routes = [];
    private static $currentAction;

    public function __construct()
    {
        $this->init();
    }

    public static function getCurrentAction()
    {
        return self::$currentAction;
    }

    public function init()
    {
        $this->setRoute("/", "Product", "index");
        $this->setRoute("products/filtered", "Product", "productFiltered");
        $this->setRoute("user/login", "User", "showLogin");
        $this->setRoute("user/check_login", "User", "checkLogin");
        $this->setRoute("user/register", "User", "showRegister");
        $this->setRoute("user/store_register", "User", "storeRegister");
        $this->setRoute("cart", "Cart", "index");
        $this->setRoute("cart/update", "Cart", "update");
        $this->setRoute("cart/reset", "Cart", "reset");
        $this->setRoute("cart/add", "Cart", "addProduct");
    }

    public function setRoute($url, $controller, $action)
    {
        if (empty($this->routes[$url])) {
            $this->routes[$url] = ["controller" => $controller, "action" => $action];
            return true;
        }
        return false;
    }

    public function getRoute($url)
    {
        return $this->routes[$url] ?? false;
    }

    public function process($url, $request)
    {
        $route = $this->getRoute($url);
        if(!$route){
            //404 error
            $controller = new \Shop\Controllers\ErrorController();
            $controller->error404($request);
            return;
        }
        $controllerName = "\\Shop\\Controllers\\" . $route['controller'] . "Controller";
        self::$currentAction = $route['action'];
        $controller = new $controllerName(new Helper($this->routes));
        $controller->{$route['action']."Action"}($request);
    }

}