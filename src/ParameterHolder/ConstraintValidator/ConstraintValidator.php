<?php

namespace Application\ParameterHolder\ConstraintValidator;

use Application\Console\Command\CommandParameters;
use Application\Factory\Factory;
use Application\ParameterHolder\ConstraintValidator\Constraint\Constraint;
use Application\Response\Response;

abstract class ConstraintValidator
{
    /** @var ConstraintBuilder */
    protected $constraintBuilder;
    private $errors = [];

    public function __construct(array $parameterConstraints)
    {
        $this->initializeConstraintBuilder($parameterConstraints);
    }

    private function initializeConstraintBuilder($parameterConstraints): void
    {
        $this->constraintBuilder = new ConstraintBuilder();

        foreach($parameterConstraints as $parameterName => $constraints)
        {
            foreach($constraints as $constraint => $options)
            {
                /** @var Constraint $constraint */
                $constraint = Factory::getInstance($constraint, [$options]);
                $constraint->build($this->constraintBuilder, $parameterName);
                $this->constraintBuilder->addConstraint($parameterName, $constraint);
            }
        }
    }

    /**
     * @param CommandParameters $commandParameters
     * @return bool
     */
    protected function isValid(CommandParameters $commandParameters): bool
    {
        foreach($commandParameters as $index => $parameter)
        {
            $parameterName = $this->constraintBuilder->getConstraintParameterName($index);

            /** @var Constraint $constraint */
            foreach($this->constraintBuilder->getConstraintsFor($parameterName) as $constraint)
            {
                if(!$constraint->isValid($parameter))
                {
                    $this->errors[] = $constraint->getErrorMessage([$parameterName]);
                }
            }
        }

        return !$this->hasErrors();
    }

    public function hasErrors(): bool
    {
        return (count($this->errors) > 0);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    abstract public function validate(): Response;
}