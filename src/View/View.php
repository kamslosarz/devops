<?php

namespace Application\View;

use Application\Config\Config;
use Application\Factory\Factory;

final class View
{
    private $vars = [];
    private $session = [];
    private $results;
    private $twig;
    private $activeUri;
    private $container;
    private $serviceContainer;

    public function __construct($vars = [], $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
        $this->vars = (array)$vars;
        $config = Config::get('twig');
        $loader = new \Twig_Loader_Filesystem($config['loader']['templates']);
        $this->twig = new \Twig_Environment($loader, [
            'cache' => $config['loader']['cache']
        ]);

        $this->extendTwig();
        $this->cacheAssets();
    }

    /**
     * @throws ViewException
     */
    private function cacheAssets()
    {
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
                                throw new ViewException(sprintf('Cannot create \'%s\' dir \'%s\'', $destDirectory, $status));
                            }
                        }

                        if(!is_writable($destDirectory))
                        {
                            throw new ViewException(sprintf('Directory \'%s\' must be writeable', $destDirectory));
                        }

                        touch($dest);
                        chmod($dest, 0777);
                    }

                    if(!is_writable($dest))
                    {
                        throw new ViewException(sprintf('Cannot write to file \'%s\'', $dest));
                    }

                    copy($fileInfo->getPathname(), $dest);
                }
            }
        }
    }

    private function extendTwig()
    {
        foreach(TwigExtensionsMap::EXTENSIONS as $extension)
        {
            $this->twig->addExtension(Factory::getInstance($extension, [$this->serviceContainer]));
        }
    }

    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param $template
     * @return string
     * @throws ViewException
     */
    public function render($template)
    {
        try
        {
            $filename = $template . '.html.twig';

            if(is_file(sprintf('%s/%s', Config::get('twig')['loader']['templates'], $filename)))
            {
                return $this->twig->render($filename, $this->vars);
            }

            return null;
        }
        catch(\Twig_Error_Loader $e)
        {
            throw new ViewException($e);
        }
        catch(\Twig_Error_Runtime $e)
        {
            throw new ViewException($e);
        }
        catch(\Twig_Error_Syntax $e)
        {
            throw new ViewException($e);
        }

        return $this->results;
    }

    /**
     * @return array
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param $session
     * @return $this
     */
    public function setSession($session)
    {
        $this->session = $session;

        return $this;
    }

    public function setActiveUri($uri)
    {
        $this->activeUri = $uri;

        return $this;
    }
}
