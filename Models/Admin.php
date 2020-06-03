<?php

namespace Shop\Models;

class Admin extends User
{
    public $isAvailable = 0;
    protected const TYPE = 2;

    public function __construct($salt, $id = null, $name = null, $email = null, $isAvailable = null)
    {
        parent::__construct($salt, $id = null, $name = null, $email);
        $this->isAvailable = $isAvailable ?? 0;
    }

    public function setAvailable($available)
    {
        $this->isAvailable = $available;
    }

    public function getType()
    {
        if (self::TYPE === 1) {
            return "customer";
        } else{
            return "admin";
        }
    }
}