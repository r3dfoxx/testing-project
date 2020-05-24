<?php

namespace Models;

class Config
{
    private static $params = [];
    private static $_instance;
    private const CONFIG_NAME = ".env";

    private function __construct ()
    {
        $handle = @fopen(dirname(__FILE__, 2)."/".self::CONFIG_NAME, "r");
        if ($handle) {
            while (!feof($handle)) {
                $line = fgets($handle);
                $parts = explode("=", $line);
                if(!empty($parts[0]) && !empty($parts[1])){
                    self::$params[trim($parts[0])] = trim($parts[1]);
                }
            }
            fclose($handle);
        }
    }

    public static function init()
    {
        if(!self::$_instance) { // If no instance then make one
            new self();
        }
        return self::$_instance;
    }

    public static function get($key)
    {
        if (empty($key)) {
            return false;
        }
        return (self::$params[$key] ?? false);
    }
}