<?php

namespace tests\Console\Command;


use Application\Console\Command\CommandParameters;
use PHPUnit\Framework\TestCase;

class CommandParametersTest extends TestCase
{
    public function testShouldSetExpectedParameters()
    {
        $commandParameters = new CommandParameters();
        $commandParameters->setExpectedParameters(['setExpectedParameters' => 'setExpectedParameters']);

        $reflectionClass = (new \ReflectionClass($commandParameters));

        $expectedParameters = $reflectionClass->getProperty('expectedParameters');
        $expectedParameters->setAccessible(true);

        $this->assertThat($expectedParameters->getValue($commandParameters),
            self::equalTo(['setExpectedParameters' => 'setExpectedParameters']));
    }
}