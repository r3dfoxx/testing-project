<?php
require_once dirname(__FILE__). "/config.php";
require_once dirname(__FILE__). "/functions.php";

$error = [];
$fieldsMap = [
    "user_name" => "Full Name",
    "email" => "Email",
    "password" => "Password",
    "confirm_password" => "Confirm Password"
];
if (!empty($_POST)) {
    unset($_POST['reg_id']);
    foreach($_POST as $k => &$v){
        $v = trim(strip_tags($v));
        if (empty($v)) {
            $error[$k][] = "Filed " . $fieldsMap[$k] . " should be filled!";
        } else {
            if ($k == "user_name" && (strlen($v) < 5 || strlen($v) > 150)) {
                $error[$k][] = "Length of " . $fieldsMap[$k] . " should be more than 5 and less than 150";
            }
            if ($k == "password" && ($v != trim(strip_tags($_POST['confirm_password'])))) {
                $error[$k][] = $fieldsMap[$k] . " should be confirmed!";
            }
            if($k == "email" && !filter_var($v, FILTER_VALIDATE_EMAIL)){
                $error[$k][] = $fieldsMap[$k] . " is not valid!";
            }
        }
    }
    unset($v);

    if (empty($error)) {
        $userId = registerUser($pdo, $_POST['user_name'], $_POST['email'], $_POST['password']);
        if(!empty($userId)){
            $_SESSION['user_id'] = $userId;
            header("Location: /index.php");
            die();
        }
    }
}

require_once dirname(__FILE__) . "/views/register.php";
?>
