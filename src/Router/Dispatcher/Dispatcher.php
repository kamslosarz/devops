<?php

namespace Application\Router\Dispatcher;


use Application\Factory\Factory;
use Application\Response\Response;

class Dispatcher
{
    protected $response;
    protected $class;
    protected $method;

    /**
     * Dispatcher constructor.
     * @param $class
     * @param $method
     * @param array $parameters
     * @throws DispatcherException
     */
    public function __construct($class, $method, $parameters = [])
    {
        $this->class = $class;
        $this->method = $method;
        $this->isValid();
        $this->class = Factory::getInstance($class, $parameters);
    }

    /**
     * @param array $parameters
     * @throws DispatcherException
     */
    public function dispatch($parameters = [])
    {
        if(is_null($this->response))
        {
            $this->response = $this->class->{$this->method}(...array_values($parameters));

            if(!($this->response instanceof Response))
            {
                $this->response = new Response($this->response);
            }
        }
    }

    /**
     * @param $controller
     * @param $action
     *
     * @throws DispatcherException
     */
    protected function isValid()
    {
        if(!class_exists($this->class))
        {
            throw new DispatcherException(sprintf('Controller class \'%s\' not exists', $this->class));
        }

        if(!method_exists($this->class, $this->method))
        {
            throw new DispatcherException(sprintf('Action \'%s\' not exists in \'%s\'', $this->method, $this->class));
        }
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}