<?php

namespace Application\Console\Command;

use Application\Console\Validator;
use Application\EventManager\Event;
use Application\Factory\Factory;
use Application\Formatter\Constraint\Constraint;
use Application\Response\ResponseTypes\ConsoleResponse;
use Application\Console\Command\CommandParameters;

abstract class Command
{
    protected $output;
    protected $event;
    protected $parameters;
    protected $commandParameters;

    public function __construct(Event $event)
    {
        $this->event = $event;
        $this->commandParameters = $event->getParameters();
    }

    protected function setOutput($output): self
    {
        $this->output = $output;

        return $this;
    }

    protected function addOutput($output): self
    {
        $this->output .= $output;

        return $this;
    }

    /**
     * @return ConsoleResponse
     */
    protected function sendOutput(): ConsoleResponse
    {
        return (new ConsoleResponse())->setContent($this->output);
    }

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