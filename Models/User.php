<?php

namespace Models;

use Models\Interfaces\Password;
use Models\Interfaces\SaveData;

class User extends AbstractUser implements Password, SaveData
{
    use Traits\FindRecord;

    protected $id;
    public $name = '';
    public $email;
    private $password;
    private $status = "Active";
    protected const TYPE = 1;
    private static $dbTable = 'users';

    public function __construct($id = null, $name = null, $email = null, $password = null)
    {
        $this->id = $id ?? null;
        $this->name = $name ?? null;
        $this->email = $email ?? null;
        $this->salt = getenv("SALT");
        if (!empty($password)) {
            $this->setPassword($password);
        }
    }

    public function lockUser()
    {
        $this->status = "Locked";
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function changePass($oldPass, $newPass)
    {
        if ($this->password == $this->encryptPass($oldPass)) {
            $this->password = $this->encryptPass($newPass);
            return true;
        } else {
            return false;
        }
    }

    public function setPassword($password)
    {
        $this->password = $this->encryptPass($password);
    }

    private function encryptPass($password)
    {
        return sha1($password . $this->salt);
    }

    function __destruct()
    {
        print "Уничтожается " . __CLASS__ . "\n";
    }

    public function __call($name, $arguments)
    {
        $allowFields = ["name", "email"];
        $incorrectMethod = 1;
        if (strpos($name, "set") === 0) {
            $paramName = strtolower(substr($name, 3));
            if (in_array($paramName, $allowFields) && !empty($arguments[0])) {
                $this->{$paramName} = $arguments[0];
                $incorrectMethod = 0;
            }
        }

        if ($incorrectMethod) {
            // Замечание: значение $name регистрозависимо.
            echo "Вызов метода '$name' "
                . implode(', ', $arguments) . "\n";
        }
    }

    public function __set($name, $value)
    {
        if ($name === "salt") {
            echo "НЕ ТРОГАЙ СОЛЬ!!";
        }
    }

    public function __toString()
    {
        return $this->id . " " . $this->name . "" . $this->email;
    }

    public function save()
    {
        $stmt = Database::getInstance()->prepare("
            INSERT INTO `users` (
                `user_name`,
                `email`,
                `password`
            )
            VALUES
                (
                    :user_name,
                    :email,
                    :password
                )"
        );
        $stmt->execute([
                           "user_name" => $this->name,
                           "email" => $this->email,
                           "password" => $this->password
                       ]);
        $this->id = Database::getInstance()->lastInsertId();
        return $this->id;
    }

    public function getType()
    {
        if (self::TYPE === 1) {
            return "customer";
        } else{
            return "admin";
        }
    }

    public static function findAll()
    {
        $items = self::findAllRecord();
        $users = [];
        foreach ($items as $item) {
            $user = new User($item['id'], $item['user_name'], $item['email']);
            $user->password = $item['password'];
            $users[] = $user;
        }
        return $users;
    }
}