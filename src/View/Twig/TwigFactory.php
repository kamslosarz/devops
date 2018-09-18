<?php

namespace Application\View\Twig;

use Application\Config\Config;
use Application\Factory\Factory;
use Application\Service\ServiceContainer\ServiceContainer;
use Application\View\Twig\TwigExtensions\TwigExtensionsMap;

abstract class TwigFactory
{
    /**
     * @param ServiceContainer $serviceContainer
     * @return \Twig_Environment
     * @throws TwigFactoryException
     */
    public static function getInstance(ServiceContainer $serviceContainer)
    {
        $config = Config::get('twig');

        $loader = new \Twig_Loader_Filesystem($config['loader']['templates']);
        $twig = new \Twig_Environment($loader, [
            'cache' => $config['loader']['cache']
        ]);

        foreach(TwigExtensionsMap::EXTENSIONS as $extension)
        {
            $twig->addExtension(Factory::getInstance($extension, [$serviceContainer]));
        }

        return $twig;
    }
}