<?php

namespace Application\Console\Command;

use Application\Logger\LoggerLevel;

abstract class ConsoleExecutable extends Command
{
    protected function executeInShell($command, $parameters = [])
    {
        $this->log(sprintf('Running ' . $command, ...$parameters), LoggerLevel::INFO);

        passthru(sprintf($command, ...$parameters), $return_var);

        if($return_var)
        {
            $this->log(sprintf('Error %s', $return_var), LoggerLevel::ERROR);
        }
    }

    private function log($message, $level)
    {
        $this->getLogger()->log($message, $level);
    }
}