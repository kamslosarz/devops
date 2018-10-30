<?php

namespace Application\Console\Command;

use Application\Console\Validator;
use Application\EventManager\Event;
use Application\Factory\Factory;
use Application\Formatter\Constraint\Constraint;
use Application\Response\ResponseTypes\ConsoleResponse;
use Application\Console\Command\Command\CommandParameters;

abstract class Command
{
    protected $output;
    protected $event;
    protected $parameters;

    public function __construct(Event $event)
    {
        $this->event = $event;
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

    /**
     * @param CommandParameters $commandParameters
     * @throws CommandException
     */
    public function validate(CommandParameters $commandParameters): void
    {
        if($commandParameters->count() !== $this->getParametersCount())
        {
            throw new CommandException(sprintf('Invalid parameters. %d expected, but %d given.', $this->getParametersCount(), $commandParameters->count()));
        }

        foreach($this->parameters as $offset => $parameter)
        {
            /** @var Constraint $constraint */
            $constraint = Factory::getInstance($parameter[1], [$commandParameters->offsetGet($offset), $parameter[0]]);

            if(!$constraint->isValid())
            {
                throw new CommandException(implode(',', $constraint->getErrors()));
            }
        }
    }

    private function getParametersCount(): int
    {
        return count($this->parameters);
    }

    abstract public function execute(CommandParameters $commandParameters): ConsoleResponse;

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