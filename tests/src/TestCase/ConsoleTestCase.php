<?php

namespace Test\TestCase;

use PHPUnit\DbUnit\TestCase;
use Test\TestCase\Traits\DatabaseTestCaseTrait;

class ConsoleTestCase extends TestCase
{
    use DatabaseTestCaseTrait;

    protected function getDataSet()
    {
        return $this->createArrayDataSet([]);
    }
}