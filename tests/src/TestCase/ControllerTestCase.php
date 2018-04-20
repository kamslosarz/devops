<?php

namespace Test\TestCase;

use Application\Container\Appender\Appender;
use Application\Container\Container;
use Application\Session\Session;
use \Mockery as m;
use PHPUnit\Framework\TestCase;

abstract class ControllerTestCase extends TestCase
{
    /**
     * @return m\MockInterface
     */
    public function getContainerMock()
    {
        return m::mock(Container::class)
            ->shouldReceive('getSession')
            ->andReturn(m::mock(Session::class))
            ->getMock();
    }

    /**
     * @return m\MockInterface
     */
    public function getAppenderMock()
    {
        return m::mock(Appender::class);
    }

}