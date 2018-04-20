<?php

namespace Application\Router\Dispatcher;


class Dispatcher
{
    private $results;
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
        $this->results = $this->class->{$this->method}($parameters);
    }

    public function getResults()
    {
        return $this->results;
    }

}