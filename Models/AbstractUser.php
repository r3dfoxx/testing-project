<?php

namespace Models;

abstract class AbstractUser
{
    protected $id;
    public $name = '';
    public $email;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public abstract function save();
}