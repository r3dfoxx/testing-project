<?php

namespace Shop\Controllers;

use Shop\Helper\Helper;
use Shop\View\View;
use Shop\Router\Router;

abstract class BaseController
{
    protected $view;
    protected $excludeBefore = [];
    protected $helper;

    public function __construct(Helper $helper)
    {
        if (!in_array(Router::getCurrentAction(), $this->excludeBefore)) {
            $this->before();
        }
        $this->view = new View($helper);
        $this->helper = $helper;
    }

    abstract protected function before();

}