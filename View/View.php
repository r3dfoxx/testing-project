<?php

namespace Shop\View;

use Shop\Helper\Helper;

class View
{
    private $ext = 'php';
    private $folder = "templates";
    private $header = "header";
    private $footer = "footer";
    private $helper;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    public function setExt(string $ext)
    {
        $this->ext = $ext;
    }

    public function setViewsFolder(string $dir)
    {
        $this->folder = $dir;
    }

    public function render(string $viewName, array $data = [])
    {
        if (!empty($data)) {
            extract($data);
        }
        //header
        require ROOT_PATH . DIRECTORY_SEPARATOR . $this->folder . DIRECTORY_SEPARATOR . $this->header . "." . $this->ext;

        require ROOT_PATH . DIRECTORY_SEPARATOR . $this->folder . DIRECTORY_SEPARATOR . $viewName . "." . $this->ext;

        //footer
        require ROOT_PATH . DIRECTORY_SEPARATOR . $this->folder . DIRECTORY_SEPARATOR . $this->footer . "." . $this->ext;
    }

    public function renderFull(string $viewName)
    {
        require ROOT_PATH . DIRECTORY_SEPARATOR . $this->folder . DIRECTORY_SEPARATOR . $viewName . "." . $this->ext;
    }
}