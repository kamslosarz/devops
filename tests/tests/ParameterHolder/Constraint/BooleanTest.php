<?php

namespace tests\ParameterHolder\Constraint;

use Application\ParameterHolder\Constraint\Boolean;
use PHPUnit\Framework\TestCase;

class BooleanTest extends TestCase
{
    public function testShouldCheckIfConstraintIsValidSuccess()
    {
        $boolean = new Boolean(true);

        $this->assertTrue($boolean->isValid());
    }

    public function testShouldCheckIfConstraintIsValidFailed()
    {
        $boolean = new Boolean(1);

        $this->assertFalse($boolean->isValid());
    }
}