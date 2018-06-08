<?php

namespace Test\Decorator;

use Application\Container\Container;
use Application\Context\Context;
use Application\Service\ServiceContainer\ServiceContainer;
use Application\View\View;

class ContainerDecorator extends Container
{
    public function __construct(ServiceContainer $serviceContainer = null, Context $context = null, View $view = null)
    {
        if($serviceContainer instanceof ServiceContainer)
        {
            $this->serviceContainer = $serviceContainer;
        }
        else
        {
            $this->serviceContainer = new ServiceContainer();
        }

        if($context instanceof Context)
        {
            $this->context = $context;
        }
        else
        {
            $this->context = new Context($this->serviceContainer);
        }

        if($view instanceof View)
        {
            $this->view = $view;
        }
        else
        {
            $this->view = new View($this->serviceContainer);
        }
    }
}