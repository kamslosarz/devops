<?php

namespace Application\Console\Command;


use Application\Factory\Factory;

abstract class Command
{
    const COMMANDS = [
        'docker' => Docker::class,
        'cache' => Cache::class
    ];

    public function isValid()
    {
        return true;
    }

    public static function getInstance($command)
    {
        if(!isset(self::COMMANDS[$command])){
            return false;
        }

        $command = self::COMMANDS[$command];

        return Factory::getInstance($command);
    }

}