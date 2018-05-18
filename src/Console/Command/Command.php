<?php

namespace Application\Console\Command;


use Application\Factory\Factory;

abstract class Command
{
    private $errors;

    const COMMAND_NAMESPACE = 'Application\Console\Command';

    /**\
     * @param $command
     * @return Command|null
     */
    public static function getInstance($command)
    {
        if(!self::exists($command))
        {
            return null;
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

}