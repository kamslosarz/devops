<?php

namespace Test\Decorator;

use Application\Container\Container;
use Application\Context\Context;
use Application\Service\ServiceContainer\ServiceContainer;
use Application\View\View;

class ContainerDecorator extends Container
{
    public function __construct(ServiceContainer $serviceContainer, Context $context, View $view)
    {
        $this->serviceContainer = $serviceContainer;
        $this->context = $context;
        $this->view = $view;
    }
}