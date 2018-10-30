<?php

namespace Application\View;

use Application\Config\Config;
use Application\ParameterHolder\ParameterHolder;
use Application\Response\Response;
use Application\Service\Logger\LoggerLevel;
use Application\Service\ServiceContainer\ServiceContainer;
use Application\View\Twig\TwigFactory;

class View
{
    private $twig;
    private $serviceContainer;
    private $viewPath;

    /**
     * View constructor.
     * @param ServiceContainer $serviceContainer
     * @throws Twig\TwigFactoryException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function __construct(ServiceContainer $serviceContainer)
    {
        $config = $serviceContainer->getService('config');
        $this->serviceContainer = $serviceContainer;
        $this->twig = TwigFactory::getInstance($serviceContainer, $config->twig);
        $this->viewPath = $config->twig['loader']['templates'];
    }

    /**
     * @param Response $response
     * @return null|string
     */
    public function render(Response $response): string
    {
        try
        {
            return $this->twig->render($response->getResource(), $response->getParameters() + ['services' => $this->serviceContainer]);
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

    private function handleViewError(\Exception $exception): string
    {
        $this->serviceContainer->getService('logger')->log(
            'ApplicationLogger',
            sprintf('View Error: %s', $exception->getMessage()),
            LoggerLevel::INFO
        );

        return $this->twig->render('error.html.twig', ['exception' => $exception]);
    }
}
