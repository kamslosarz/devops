<?php

namespace Application\Console;


class ConsoleParameters
{
    const COMMAND_PATTERN_REGEX = '/^([A-Za-z]+):([A-Za-z]+)$/ixAs';

    private $parameters;
    private $command;

    public function __construct($parameters)
    {
        if(isset($parameters[1]))
        {
            $this->command = $this->getCommandClassName($parameters[1]);

            unset($parameters[0]);
            unset($parameters[1]);
        }

        $this->parameters = $parameters;
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function getCommandClassName($string)
    {
        $name = preg_replace_callback(self::COMMAND_PATTERN_REGEX, function ($args)
        {
            return sprintf('%s\\%s', ucfirst(strtolower($args[1])), ucfirst(strtolower($args[2])));
        }, $string);

        return $name;
    }
}