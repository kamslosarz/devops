<?php

namespace Application\View;

use Application\Config\Config;
use Application\Factory\Factory;
use Application\Service\Logger\LoggerLevel;

class View
{
    private $twig;
    private $serviceContainer;

    public function __construct($serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
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

    /**
     * @param $template
     * @param $vars
     * @return null|string
     * @throws ViewException
     */
    public function render($template, array $vars = [])
    {
        try
        {
            $filename = $template . '.html.twig';

            if(is_file(sprintf('%s/%s', Config::get('twig')['loader']['templates'], $filename)))
            {
                return $this->twig->render($filename, $vars);
            }

            return null;
        }
        catch(\Twig_Error_Loader $e)
        {
            return $this->handleViewError($e);
        }
        catch(\Twig_Error_Runtime $e)
        {
            return $this->handleViewError($e);
        }
        catch(\Twig_Error_Syntax $e)
        {
            return $this->handleViewError($e);
        }
    }

    /**
     * @param \Exception $exception
     * @return null|string
     * @throws ViewException
     */
    private function handleViewError(\Exception $exception)
    {
        $this->serviceContainer->getService('logger')->log(
            'ApplicationLogger',
            sprintf('View Error: %s', $exception->getMessage()),
            LoggerLevel::INFO
        );

        return $this->render('error', ['exception' => $exception]);
    }
}
