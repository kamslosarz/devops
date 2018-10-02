<?php

namespace Application\Router\Dispatcher;

use Application\Console\ConsoleParameters;
use Application\ParameterHolder\ParameterHolder;
use Application\Response\Response;

class ConsoleDispatcher extends Dispatcher
{
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

    /**
     * @param ConsoleParameters $parameterHolder
     */
    public function dispatch(ParameterHolder $parameterHolder)
    {
        if(is_null($this->response))
        {
            $this->response = $this->class->{$this->method}($parameterHolder->getCommandParameters());

            if(!($this->response instanceof Response))
            {
                $this->response = new Response($this->response);
            }
        }
    }
}