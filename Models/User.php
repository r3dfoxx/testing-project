<?php

namespace Shop\Models;

use Shop\Models\Interfaces\Password;
use Shop\Models\Interfaces\SaveData;

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
    private static $incorrectLoginAttempts = 0;
    private static $lockedTime;
    private const LOCK_TIME = 10;


    public function __construct($id = null, $name = null, $email = null, $password = null)
    {
        $this->id = $id ?? null;
        $this->name = $name ?? null;
        $this->email = $email ?? null;
        if (!empty($password)) {
            $this->setPassword($password);
        }
    }

    public static function lockUser($userId)
    {
        $stmt = Database::getInstance()->prepare("
            UPDATE
                `users`
            SET
                `status` = 'Locked',
                `locked_time` = NOW()
            WHERE
                `id` = :id
            "
        );
        $stmt->execute(["id" => $userId]);
        //$this->status = "Locked";
    }

    public function getId()
    {
        return $this->id;
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
        return sha1($password . getenv('SALT'));
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
            //echo "НЕ ТРОГАЙ СОЛЬ!!";
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

    public function activate()
    {
        $stmt = Database::getInstance()->prepare("
            UPDATE
                `users`
            SET
                `status` = 'Active'
            WHERE
                `id` = :id
            "
        );
        $stmt->execute(["id" => $this->id]);
        $this->status = "Active";
        return true;
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

    public static function login($email, $password)
    {
        $response = [];
        if (empty($email) || empty($password)) {
            return false;
        }
        $stmt = Database::getInstance()->prepare("
            SELECT
                `id`,
                `user_name`,
                `email`,
                `password`,
                `status`,
                `incorrect_login_attempts`,
                `locked_time`
            FROM
                `users`
            WHERE
                `email` = :email
            AND `status` in ('Active', 'Locked')
        ");
        $stmt->execute(["email" => $email]);
        $user = $stmt->fetch();
        if (empty($user)) {
            $response['success'] = 0;
            $response['error'] = "Incorrect login credentials";
            return $response;
        }
        if($user['status'] == "Locked"){
            $timeDiff = time() - (strtotime($user['locked_time']) + 10*60);
            if($timeDiff > 0) {
                User::resetIncorrectLoginAttempts($user['id']);
                $user['incorrect_login_attempts'] = 0;
            } else {
                $response['success'] = 0;
                $response['error'] = "Incorrect login credentials. User was locked for next " . abs(ceil($timeDiff / 60)) . " minutes";
                return $response;
            }
        }
        if ($user['password'] == self::encryptPass($password)) {
            $response['success'] = 1;
            $user = new User($user['id'], $user['user_name'], $user['email'], $password);
            $response['data'] = $user;
            if($user->status = "Locked") {
                $user->activate();
            }
            return $response;
            //reset incorrect login attempts
        } else {
            //some logic for lock user
            if ($user['incorrect_login_attempts'] >= 2) {
                //lock user
                self::lockUser($user['id']);
                $response['success'] = 0;
                $response['error'] = "Incorrect login credentials. User was locked for next " . self::LOCK_TIME . " minutes";
                return $response;
            } else {
                self::increaseIncorrectLoginAttempts($user['id']);
                $response['success'] = 0;
                $response['error'] = "Incorrect login credentials";
                return $response;
            }
        }
    }

    public static function increaseIncorrectLoginAttempts($userId)
    {
        $stmt = Database::getInstance()->prepare("
            UPDATE
                    `users`
                SET
                    `incorrect_login_attempts` = incorrect_login_attempts + 1
                WHERE
                    `id` = :id
            "
        );
        $stmt->execute(["id" => $userId]);
        return true;
    }

    public static function resetIncorrectLoginAttempts($userId)
    {
        $stmt = Database::getInstance()->prepare("
            UPDATE
                    `users`
                SET
                    `incorrect_login_attempts` = 0
                WHERE
                    `id` = :id
            "
        );
        $stmt->execute(["id" => $userId]);
        return true;
    }
}