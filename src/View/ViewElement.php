<?php

namespace Application\View;

use View\ViewElementInterface;

class ViewElement implements ViewElementInterface
{
    protected $viewName;
    protected $parameters;

    public function __construct($viewName, $parameters = [])
    {
        $this->viewName = $viewName;
        $this->parameters = $parameters;
    }

}
