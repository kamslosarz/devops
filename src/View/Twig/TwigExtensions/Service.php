<?php

namespace Application\View\Twig\TwigExtensions;

use Application\Service\ServiceInterface;

class Service extends Extension implements \Twig_Extension_GlobalsInterface
{
    public function getGlobals(): array
    {
        return [
            'services' => $this
        ];
    }

    public function __call($name, $arguments): ServiceInterface
    {
        return $this->getService($name);
    }

    public function getService($name): ServiceInterface
    {
        return $this->serviceContainer->getService($name);
    }
}