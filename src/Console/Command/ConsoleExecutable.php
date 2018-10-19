<?php

namespace Application\Console\Command;

abstract class ConsoleExecutable extends Command
{
    protected function executeInShell($command, $parameters = [])
    {
        if(!empty($parameters))
        {
            passthru(sprintf($command, ...$parameters), $return_var);
        }
        else
        {
            passthru($command, $return_var);
        }

        return $return_var;
    }
}