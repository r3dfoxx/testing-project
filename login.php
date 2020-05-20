<?php
require_once dirname(__FILE__). "/config.php";
require_once dirname(__FILE__). "/functions.php";

$error = [];
if (!empty($_POST)) {
    unset($_POST['login']);
    foreach($_POST as $k => $v){
        $v = trim(strip_tags($v));
        if (empty($v)) {
            $error[$k][] = "Field " . ucfirst(str_replace("_", " ", $k)) . " should be filled!";
        }
    }

    if (empty($error)) {
        $userId = loginUser($pdo, $_POST['email'], $_POST['password']);
        if(!empty($userId)){
            $_SESSION['user_id'] = $userId;
            header("Location: /index.php");
            die();
        } else{
            $error["email"][] = "Login or Password is incorrect!";
        }
    }
}

require_once dirname(__FILE__) . "/views/login.php";
?>
