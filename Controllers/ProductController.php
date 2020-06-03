<?php

namespace Shop\Controllers;

use Shop\Models\Cart;
use Shop\Models\CartProduct;
use Shop\Models\Product;
use Shop\Models\Category;

class ProductController extends BaseController
{

    protected function before()
    {

    }

    public function indexAction(array $request = null)
    {
        $products = Product::findAll();
        if(!empty($_SESSION['cart_id'])){

        }
        $categories = Category::findAll();

        $this->view->render("products", ["products" => $products, "categories" => $categories]);
    }

    public function productFilteredAction(array $request = null)
    {
        if (!empty($request['categories'])) {
            $products = Product::getProductsByCategories($request['categories']);
        } else {
            $this->helper->redirect("/");
        }
        $categories = Category::findAll();

        $this->view->render("products", ["products" => $products, "categories" => $categories]);
    }

}