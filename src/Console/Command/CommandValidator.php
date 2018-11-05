<?php

namespace Application\Console\Command;

use Application\EventManager\Event;
use Application\ParameterHolder\ConstraintValidator\ConstraintException;
use Application\ParameterHolder\ConstraintValidator\ConstraintValidator;
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
     * @return Response
     * @throws CommandException
     * @throws ConstraintException
     */
    public function validate(): Response
    {
        $parameters = $this->event->getParameters();

        if($parameters->count() < $this->constraintBuilder->getRequiredCount())
        {
            throw new ConstraintException(sprintf(
                'Too few arguments. %s are required',
                implode(', ', array_keys($this->constraintBuilder->getRequiredConstraints()))
            ));
        }

        if(!$this->isValid($parameters))
        {
            throw new CommandException(implode(', ', $this->getErrors()));
        }

        return new ConsoleResponse();
    }
}