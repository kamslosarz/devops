<?php

namespace Application\ParameterHolder\ConstraintValidator;

use Application\ParameterHolder\ConstraintValidator\Constraint\Constraint;

class ConstraintBuilder
{
    private $constraints = [];
    private $requiredConstraints = null;

    public function addConstraint($parameterName, Constraint $constraint): self
    {
        $this->constraints[$parameterName][] = $constraint;

        return $this;
    }

    public function getConstraints(): array
    {
        return $this->constraints;
    }

    public function getConstraintsFor($parameterName): array
    {
        return $this->constraints[$parameterName];
    }

    public function getConstraintParameterName($index): string
    {
        return array_keys($this->constraints)[$index];
    }

    public function getRequiredCount(): int
    {
        return count($this->getRequiredConstraints());
    }

    public function getRequiredConstraints()
    {
        if(is_null($this->requiredConstraints))
        {
            $this->requiredConstraints = array_filter($this->constraints, function ($constraints)
            {
                return !empty(array_filter($constraints, function (Constraint $constraint)
                {
                    return !$constraint->isNotRequired();
                }));
            });
        }

        return $this->requiredConstraints;
    }
}