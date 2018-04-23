<?php

namespace Application\Console\Command;


use Application\Factory\Factory;

abstract class Command
{
    const COMMANDS = [
        'docker' => Docker::class,
        'cache' => Cache::class,
        'admin' => Admin::class
    ];

    private $errors;

    public static function getInstance($command)
    {
        if(!isset(self::COMMANDS[$command]))
        {
            return false;
        }

        $command = self::COMMANDS[$command];

        return Factory::getInstance($command);
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