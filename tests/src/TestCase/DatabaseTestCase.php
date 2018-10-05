<?php

namespace Test\TestCase;

use PHPUnit\DbUnit\TestCase;
use Test\TestCase\Traits\DatabaseTestCaseTrait;

abstract class DatabaseTestCase extends TestCase
{
    use DatabaseTestCaseTrait;
}