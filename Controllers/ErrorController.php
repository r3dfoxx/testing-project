<?php

namespace Shop\Controllers;

class ErrorController extends BaseController
{

    protected function before()
    {

    }

    public function error404(array $request = null)
    {
        $this->view->renderFull("404");
    }

}