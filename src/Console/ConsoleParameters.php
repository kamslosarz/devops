<?php

namespace Application\Console;


class ConsoleParameters
{
    const COMMAND_PATTERN_REGEX = '/^[A-Za-z]+:[A-Za-z]+$/';

    private $parameters;
    private $command;
    private $action;

    public function __construct($parameters)
    {
        if(isset($parameters[1]) && preg_match(self::COMMAND_PATTERN_REGEX, $parameters[1]))
        {
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