<?php

namespace Shop\Models\Interfaces;

interface Password
{
    public function setPassword($password);
    public function changePass($oldPass, $newPass);
}