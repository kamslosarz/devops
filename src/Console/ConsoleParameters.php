<?php

namespace Application\Console;


class ConsoleParameters
{
    private $parameters;
    private $command;
    private $action;

    public function __construct($parameters)
    {
        if (isset($parameters[1]) && preg_match('/^[a-z]+:[a-z]+$/', $parameters[1])) {
            $tmp = explode(':', $parameters[1]);

            $this->command = $tmp[0];
            $this->action = $tmp[1];

            unset($parameters[0]);
            unset($parameters[1]);
        }

        $this->parameters = $parameters;
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

}