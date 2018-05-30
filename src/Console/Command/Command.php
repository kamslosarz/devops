<?php

namespace Application\Console\Command;

use Application\Console\CommandException;
use Application\Factory\Factory;
use Application\Logger\Logger;

abstract class Command
{
    private $errors;
    private $logger;

    const COMMAND_NAMESPACE = 'Application\Console\Command\Command';

    /**
     * @param $command
     * @return mixed
     * @throws \Application\Console\Command\CommandException
     */
    public static function getInstance($command)
    {
        if(!self::exists($command))
        {
            throw new \Application\Console\Command\CommandException(sprintf('Command \'%s\' not found', $command));
        }

        $command = self::getCommandNamespace($command);

        return Factory::getInstance($command);
    }

    /**
     * @param $command
     * @return bool
     */
    private static function exists($command)
    {
        return class_exists(self::getCommandNamespace($command));
    }

    private static function getCommandNamespace($command)
    {
        return sprintf('%s\%s', self::COMMAND_NAMESPACE, $command);
    }

    public function setError($error)
    {
        $this->errors[] = $error;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getLogger()
    {
        if(!($this->logger instanceof Logger))
        {
            $this->logger = new Logger('ConsoleLogger');
        }

        return $this->logger;
    }

    abstract public function isValid();
}