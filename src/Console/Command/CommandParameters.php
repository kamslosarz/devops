<?php

namespace Application\Console\Command;

use Application\ParameterHolder\ParameterHolder;

class CommandParameters extends ParameterHolder
{
    private $expectedParameters;

    public function setExpectedParameters($expectedParameters): self
    {
        $this->expectedParameters = $expectedParameters;

        return $this;
    }
}