<?php
namespace Application\View\TwigExtensions;

use Application\Container\Container;

class Extension extends \Twig_Extension
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

}