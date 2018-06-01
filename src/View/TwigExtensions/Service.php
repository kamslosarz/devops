<?php

namespace Application\View\TwigExtensions;

class Service extends Extension implements \Twig_Extension_GlobalsInterface
{
    public function getGlobals()
    {
        return [
            'services' => $this
        ];
    }

    public function __call($name, $arguments)
    {
        return $this->getService($name);
    }

    public function getService($name)
    {
        return $this->container->getServiceContainer()->getService($name);
    }
}