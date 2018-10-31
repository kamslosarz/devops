<?php

namespace Application\ParameterHolder\Constraint;

use Application\Factory\Factory;
use Application\Response\Response;

abstract class ConstraintValidator
{
    private $constraints;
    private $errors = [];

    public function __construct(array $constraints)
    {
        $this->constraints = $constraints;
    }

    protected function isValid(array $parameters): bool
    {
        /** @var Constraint $constraint */
        $id = 0;
        foreach($this->constraints as $constraint)
        {
            $constraintStructure = $this->getConstraintStructure($constraint);
            $parameterValue = $parameters[$id++] ?? null;

            if(!$constraintStructure->isOptional || ($constraintStructure->isOptional && $parameterValue))
            {
                $constraint = Factory::getInstance($constraintStructure->class, [
                    $parameterValue ?? null, $constraintStructure->parameterName
                ]);

                if(!$constraint->isValid())
                {
                    $this->errors = array_merge($constraint->getErrors(), $this->errors);
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

    private function getConstraintStructure($constraint)
    {
        return (object)[
            'parameterName' => $constraint[0],
            'class' => $constraint[1],
            'isOptional' => $constraint[2] ?? null
        ];
    }
}