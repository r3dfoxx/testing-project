<?php

namespace Models\Interfaces;

interface Password
{
    public function setPassword($password);
    public function changePass($oldPass, $newPass);
}