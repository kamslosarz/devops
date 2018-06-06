<?php

use Application\Container\Container;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testShouldConstructContainer()
    {
        $logger = m::mock(\Application\Service\Logger\Logger::class);

        $logger->shouldReceive('log')
            ->atLeast(1)
            ->andReturns(true);

        $container = new Container();

        $this->assertInstanceOf(Container::class, $container);
    }

    public function testShouldInvokeAndReturnsResponse()
    {
        $container = new Container();
        $container();

        $this->assertTrue(strlen($container->getResults()->getContent()) > 1000);
    }
}