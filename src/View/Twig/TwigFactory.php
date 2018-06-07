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

        $directories = [
            Config::get('twig')['loader']['templates'] . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'assets',
            Config::get('twig')['loader']['templates'] . DIRECTORY_SEPARATOR . 'assets'
        ];

        foreach($directories as $dir)
        {
            /** @var \SplFileInfo $fileInfo */
            foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir), \RecursiveIteratorIterator::SELF_FIRST) as $fileInfo)
            {
                if($fileInfo->isFile())
                {
                    $dest = str_replace(
                        Config::get('twig')['loader']['templates'],
                        sprintf('%s/assets', Config::get('web_dir')),
                        str_replace('/assets', '', $fileInfo->getPathname())
                    );
                    if(!is_file($dest))
                    {
                        $destDirectory = dirname($dest);
                        if(!is_dir($destDirectory))
                        {
                            $status = mkdir($destDirectory, 0777, true);

                            if(!$status)
                            {
                                throw new TwigFactoryException(sprintf('Cannot create \'%s\' dir \'%s\'', $destDirectory, $status));
                            }
                        }

                        if(!is_writable($destDirectory))
                        {
                            throw new TwigFactoryException(sprintf('Directory \'%s\' must be writeable', $destDirectory));
                        }

                        touch($dest);
                        chmod($dest, 0777);
                    }

                    if(!is_writable($dest))
                    {
                        throw new TwigFactoryException(sprintf('Cannot write to file \'%s\'', $dest));
                    }

                    copy($fileInfo->getPathname(), $dest);
                }
            }
        }

        foreach(TwigExtensionsMap::EXTENSIONS as $extension)
        {
            $twig->addExtension(Factory::getInstance($extension, [$serviceContainer]));
        }

        return $twig;
    }
}