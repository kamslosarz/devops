<?php

namespace Application\Router\Dispatcher;


use Application\Response\Response;

class Dispatcher
{
    private $response;
    private $class;
    private $method;

    public function __construct($class, $method)
    {
        $this->class = $class;
        $this->method = $method;
    }

    /**
     * @param $parameters
     */
    public function dispatch($parameters = [])
    {
        if(is_null($this->response))
        {
            $this->response = $this->class->{$this->method}(...$parameters);

            if(!($this->response instanceof Response))
            {
                $this->response = new Response($this->response);
            }
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