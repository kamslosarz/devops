<?php

namespace Application\View;

use Application\Config\Config;
use Application\Service\Logger\LoggerLevel;
use Application\View\Twig\TwigFactory;

class View
{
    private $twig;
    private $serviceContainer;
    private $viewPath;

    /**
     * View constructor.
     * @param $serviceContainer
     * @throws Twig\TwigFactoryException
     */
    public function __construct($serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
        $this->twig = TwigFactory::getInstance($serviceContainer, Config::get('twig'));
        $this->viewPath = Config::get('twig')['loader']['templates'];
    }

    /**
     * @param ViewElement $viewElement
     * @return null|\string
     */
    public function render(ViewElement $viewElement)
    {
        try
        {
            $filename = sprintf('%s.html.twig', $viewElement->getViewName());

            if(is_file(sprintf('%s/%s', $this->viewPath, $filename)))
            {
                return $this->twig->render($filename, $viewElement->getParameters());
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

    private function handleViewError(\Exception $exception)
    {
        $this->serviceContainer->getService('logger')->log(
            'ApplicationLogger',
            sprintf('View Error: %s', $exception->getMessage()),
            LoggerLevel::INFO
        );

        return $this->twig->render('error.html.twig', ['exception' => $exception]);
    }
}
