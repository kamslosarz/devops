<?php

namespace Application\Console;

use Application\Console\Command\Command\CommandParameters;
use Application\ParameterHolder\ParameterHolder;

class ConsoleParameters extends ParameterHolder
{
    const COMMAND_PATTERN = '/([A-Za-z1-9\-]+):([A-Za-z1-9\-]+)([:]|$)/';
    const COMMAND_NAME_PATTERN = '/\-([a-z]){1}/';
    const CLASS_LAST_NAMESPACE_FRAGMENT = '/(\\\)([a-zA-Z0-9]+)$/';

    private $command;
    private $commandParameters;

    public function __construct(array $parameters = [])
    {
        if(isset($parameters[1]))
        {
            $this->command = $this->getCommandClassName($parameters[1]);

            unset($parameters[0]);
            unset($parameters[1]);
        }


        parent::__construct(array_values($parameters));
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function getCommandParameters()
    {
        if($this->commandParameters instanceof CommandParameters)
        {
            return $this->commandParameters;
        }
        else
        {
            $this->commandParameters = new CommandParameters($this->parameters);

            return $this->commandParameters;
        }
    }

    public function getCommandClassName($string)
    {
        $name = preg_replace_callback(self::COMMAND_PATTERN, function ($args)
        {
            return sprintf('%s\\%s%s', self::normalizeCommand($args[1]), $args[2], $args[3]);

        }, $string, 1, $count);

        if($count)
        {
            return $this->getCommandClassName($name);
        }

        return preg_replace_callback(self::CLASS_LAST_NAMESPACE_FRAGMENT, function ($args)
        {
            return sprintf('%s%s', $args[1], self::normalizeCommand($args[2]));
        }, $name);
    }

    public static function normalizeCommand($string)
    {
        return preg_replace_callback(self::COMMAND_NAME_PATTERN, function ($letter)
        {
            if(!isset($letter[1]))
            {
                return null;
            }

            return strtoupper($letter[1]);

        }, ucfirst(strtolower($string)));
    }
}