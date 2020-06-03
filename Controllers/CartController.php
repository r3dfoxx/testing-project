<?php

namespace Shop\Controllers;

use Shop\Models\Cart;
use Shop\Models\CartProduct;
use Shop\Models\Product;

class CartController extends BaseController
{

    protected $excludeBefore = ['addProduct'];

    protected function before()
    {
        if (empty($_SESSION['cart_id'])) {
            $this->helper->redirect("/");
        }
    }

    public function indexAction(array $request = [])
    {
        $userId = $_SESSION['user_id'] ?? 0;
        $cart = new Cart($_SESSION['cart_id'], $userId);
        $totalPrice = $cart->getTotalPrice();
        $this->view->render("cart", ["cart" => $cart, "totalPrice" => $totalPrice]);
    }

    public function updateAction(array $request = [])
    {
        if (!empty($request)) {
            $userId = $_SESSION['user_id'] ?? 0;
            $cart = new Cart($_SESSION['cart_id'], $userId);
            unset($request['update']);
            foreach ($request as $k => $val){
                $id = explode("_", $k)[1];
                $cart->updateProductQuantity($id, $val);
            }
            $this->helper->redirect("/cart");
        }
    }

    public function resetAction(array $request = [])
    {
        if (!empty($_SESSION['cart_id'])) {
            $cartId = $_SESSION['cart_id'];
            $userId = $_SESSION['user_id'] ?? 0;
            $cart = new Cart($cartId, $userId);
            $cart->delete();
            unset($_SESSION['cart_id']);
        }
        $this->helper->redirect("/");
    }

    public function addProductAction(array $request = [])
    {
        if (empty($request['products'])) {
            $this->helper->redirect("/");
        }
        foreach ($request['products'] as $product) {
            $userId = $_SESSION['user_id'] ?? 0;
            if (empty($_SESSION['cart_id'])) {
                $cart = new Cart(null, $userId);
                $cartId = $cart->save();
                $_SESSION['cart_id'] = $cartId;
            } else{
                $cartId = $_SESSION['cart_id'];
                $cart = new Cart($cartId, $userId);
            }
            if(!$this->helper->checkProductInCart($product)) {
                $product = new CartProduct(Product::find($product), 1, $cartId);
                $product->save();
            }
        }
        $this->helper->redirect("/cart");
    }

}