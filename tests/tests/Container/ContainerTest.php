<?php

use Application\Container\Container;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testShouldConstructContainer()
    {
        $logger = m::mock(\Application\Logger\Logger::class);

        $logger->shouldReceive('log')
            ->atLeast(1)
            ->andReturns(true);

        $container = new Container($logger);

        $this->assertInstanceOf(Container::class, $container);
    }

    public function testShouldInvokeAndReturnsResponse()
    {
        $logger = m::mock(\Application\Logger\Logger::class);
        $logger->shouldReceive('log')
            ->atLeast(1)
            ->andReturns(true);

        $container = new Container($logger);
        $response = $container();

        $this->assertTrue(strlen($response->getResults()) > 1000);
    }
}