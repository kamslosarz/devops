<?php

namespace tests\Context;

use Application\Container\Appender\Appender;
use Application\Context\Context;
use Application\Router\Router;
use PHPUnit\Framework\TestCase;
use Test\TestCase\Traits\ServiceContainerMockTrait;


class ContextTest extends TestCase
{
    use ServiceContainerMockTrait;

    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function testShouldConstructContext()
    {
        $context = new Context($this->getServiceContainerMock());

        $this->assertInstanceOf(Context::class, $context);
    }

    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function testShouldReturnNulledRouter()
    {
        $context = new Context($this->getServiceContainerMock());

        $this->assertInstanceOf(Router::class, $context->getRouter());
    }

    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function testShouldReturnAppender()
    {
        $context = new Context($this->getServiceContainerMock());

        $this->assertInstanceOf(Appender::class, $context->getAppender());
    }
}