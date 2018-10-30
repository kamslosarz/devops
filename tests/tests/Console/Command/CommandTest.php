<?php

namespace tests\Console\Command;

use Application\Console\Command\Command\CommandParameters;
use Application\EventManager\Event;
use PHPUnit\Framework\TestCase;
use Test\Decorator\CommandDecorator;
use Mockery as m;

class CommandTest extends TestCase
{
    public function testShouldInvokeCommand()
    {
        $event = new Event();
        $command = new CommandDecorator($event);
        $response = $command->execute(m::mock(CommandParameters::class));
        $this->assertThat($response->getContent(), self::equalTo('Test command invoked'));
    }
}