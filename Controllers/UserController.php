<?php

namespace Shop\Controllers;

use Shop\Models\User;

class UserController extends BaseController
{

    protected function before()
    {
        if (!empty($_SESSION['user_id'])) {
            $this->helper->redirect("/");
        }
    }

    public function showLoginAction(array $request = [])
    {
        $this->view->render("login", $request);
    }

    public function checkLoginAction(array $request = [])
    {
        $error = [];
        if (!empty($request)) {
            unset($request['login']);
            foreach($request as $k => $v){
                $v = trim(strip_tags($v));
                if (empty($v)) {
                    $error[$k][] = "Field " . ucfirst(str_replace("_", " ", $k)) . " should be filled!";
                }
            }

            if (empty($error)) {
                $user = User::login($request['email'], $request['password']);
                if ($user['success']) {
                    $_SESSION['user_id'] = $user['data']->getId();
                    $this->helper->redirect("/");
                } else{
                    $error["email"][] = $user['error'];
                }
            }
        }
        $this->showLoginAction(["error" => $error]);
    }

    public function showRegisterAction(array $request = [])
    {
        $this->view->render("register", $request);
    }

    public function storeRegisterAction(array $request = [])
    {
        $error = [];
        $fieldsMap = [
            "user_name" => "Full Name",
            "email" => "Email",
            "password" => "Password",
            "confirm_password" => "Confirm Password"
        ];

        if (!empty($request)) {
            unset($request['reg_id']);
            foreach($request as $k => &$v){
                $v = trim(strip_tags($v));
                if (empty($v)) {
                    $error[$k][] = "Filed " . $fieldsMap[$k] . " should be filled!";
                } else {
                    if ($k == "user_name" && (strlen($v) < 5 || strlen($v) > 150)) {
                        $error[$k][] = "Length of " . $fieldsMap[$k] . " should be more than 5 and less than 150";
                    }
                    if ($k == "password" && ($v != trim(strip_tags($request['confirm_password'])))) {
                        $error[$k][] = $fieldsMap[$k] . " should be confirmed!";
                    }
                    if($k == "email" && !filter_var($v, FILTER_VALIDATE_EMAIL)){
                        $error[$k][] = $fieldsMap[$k] . " is not valid!";
                    }
                }
            }
            unset($v);

            if (empty($error)) {
                $user = new User(null, $request['user_name'], $request['email'], $request['password']);
                $userId = $user->save();
                if(!empty($userId)){
                    $_SESSION['user_id'] = $userId;
                    $this->helper->redirect("/");
                }
            }
        }
        $this->showRegisterAction(["error" => $error]);
    }

}