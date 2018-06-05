<?php

namespace Application\Router\Dispatcher;


class ConsoleDispatcher extends Dispatcher
{
    public function __construct($class, $method, $parameters = [])
    {
        $this->class = $class;
        $this->method = $method;
        $this->isValid();
    }

    /**
     * @param $controller
     * @param $action
     * @throws DispatcherException
     */
    protected function isValid()
    {
        if(!method_exists($this->class, $this->method))
        {
            throw new DispatcherException(sprintf('Action \'%s\' not exists in \'%s\'', $this->method, $this->class));
        }
    }
}