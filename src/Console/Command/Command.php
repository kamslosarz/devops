<?php

namespace Application\Console\Command;

use Application\Factory\Factory;
use Application\Response\ResponseTypes\ConsoleResponse;
use Application\Service\ServiceContainer\ServiceContainer;

abstract class Command
{
    private $errors;
    private $serviceContainer;

    const COMMAND_NAMESPACE = 'Application\Console\Command\Command';
    private $output;

    /**
     * @param $command
     * @return mixed|null
     */
    public static function getCommand($command)
    {
        if(!self::exists($command))
        {
            return null;
        }

        $command = self::getCommandNamespace($command);

        return Factory::getInstance($command);
    }

    public function __construct()
    {
        $this->serviceContainer = new ServiceContainer();
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

    /**
     * @return mixed
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function getLogger()
    {
        return $this->serviceContainer->getService('logger');
    }

    public function setOutput($output)
    {
        $this->output = $output;

        return $this;
    }

    public function addOutput($output)
    {
        $this->output .= $output;

        return $this;
    }

    protected function sendOutput()
    {
        return (new ConsoleResponse())->setContent($this->output);
    }

    public function isValid()
    {
        return true;
    }
}