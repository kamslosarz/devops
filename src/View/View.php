<?php

namespace Application\View;

use Application\Config\Config;
use Application\Service\Logger\LoggerLevel;
use Application\View\Twig\TwigFactory;

class View
{
    private $twig;
    private $serviceContainer;

    /**
     * View constructor.
     * @param $serviceContainer
     * @throws Twig\TwigFactoryException
     */
    public function __construct($serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
        $this->twig = TwigFactory::getInstance($serviceContainer);
    }

    /**
     * @param $template
     * @param array $vars
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
