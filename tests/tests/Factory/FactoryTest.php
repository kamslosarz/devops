<?php

namespace tests\Factory;

use Application\Factory\Factory;
use PHPUnit\Framework\TestCase;
use Test\Decorator\ServiceDecorator;

class FactoryTest extends TestCase
{
    public function testShouldGetInstance()
    {
        $class = Factory::getInstance(ServiceDecorator::class, ['parameters']);

        $this->assertThat($class, self::isInstanceOf(ServiceDecorator::class));
        $this->assertThat($class->getParameters(), self::equalTo('parameters'));
    }
}