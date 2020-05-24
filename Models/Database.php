<?php

namespace Models;

class Database
{
    private static $_instance;

	private function __construct () {
        self::$_instance = new \PDO(
            'mysql:host=' . getenv("DB_HOST") . ';dbname=' . getenv("DB_NAME"),
            getenv("DB_USER"),
            getenv("DB_PASS"),
            [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ]
        );

    }

    public static function getInstance()
    {
        if(!self::$_instance) { // If no instance then make one
            new self();
        }
        return self::$_instance;
    }
}