<?php

namespace Application\Console\Command;

use Application\EventManager\Event;
use Application\ParameterHolder\Constraint\ConstraintValidator;
use Application\Response\Response;
use Application\Response\ResponseTypes\ConsoleResponse;

class CommandValidator extends ConstraintValidator
{
    private $event;

    /**
     * CommandValidator constructor.
     * @param Event $event
     * @param array $constraints
     */
    public function __construct(Event $event, array $constraints)
    {
        parent::__construct($constraints);

        $this->event = $event;
    }

    /**
     * @throws CommandException
     */
    public function validate(): Response
    {
        if(!$this->isValid($this->event->getParameters()->toArray()))
        {
            throw new CommandException(implode(', ', $this->getErrors()));
        }

        return new ConsoleResponse();
    }
}