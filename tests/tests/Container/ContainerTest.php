<?php

use Application\Container\Container;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    use \Test\TestCase\Traits\ServiceContainerMockBuilderTrait;

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

    /**
     * @throws \Application\Router\RouteException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Application\View\ViewException
     * @throws \Response\ResponseTypes\RedirectResponseException
     */
    public function testShouldReturnRedirectResponseToDefaultAction()
    {
        $serviceContainerMockBuilder = $this->getServiceContainerMockBuilder();
        $serviceContainerMockBuilder->setAccessCheckerMock(
            m::mock(\Application\Service\AccessChecker\AccessChecker::class)
                ->shouldReceive('hasAccess')
                ->once()
                ->withArgs(['Admin\AdminController:index'])
                ->andReturn(true)
                ->getMock()
                ->shouldReceive('hasAccess')
                ->once()
                ->andReturn(false)
                ->getMock()
        );
        $contextMock = m::mock(\Application\Context\Context::class)
            ->shouldReceive('__invoke')
            ->andThrow(\Application\Service\AccessChecker\AccessDeniedException::class, 'Access denied to \'AdminController:indexAction\'')
            ->getMock();

        $viewMock = m::mock(\Application\View\View::class);

        $container = new \Test\Decorator\ContainerDecorator(
            $serviceContainerMockBuilder->build(),
            $contextMock,
            $viewMock
        );

        $container();

        /** @var \Application\Response\ResponseTypes\RedirectResponse $response */
        $response = $container->getResults();

        $this->assertInstanceOf(\Application\Response\ResponseTypes\RedirectResponse::class, $response);
        $this->assertEquals(['Location: /admin/index'], $response->getHeaders());
    }

    /**
     * @throws \Application\Router\RouteException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Application\View\ViewException
     * @throws \Response\ResponseTypes\RedirectResponseException
     */
    public function testShouldReturnRedirectResponseToLoginAction()
    {
        $serviceContainerMockBuilder = $this->getServiceContainerMockBuilder();
        $serviceContainerMockBuilder->setAccessCheckerMock(
            m::mock(\Application\Service\AccessChecker\AccessChecker::class)
                ->shouldReceive('hasAccess')
                ->twice()
                ->andReturn(true)
                ->getMock()
        );
        $contextMock = m::mock(\Application\Context\Context::class)
            ->shouldReceive('__invoke')
            ->andThrow(\Application\Service\AccessChecker\AccessDeniedException::class, 'Access denied to \'AdminController:loginAction\'')
            ->getMock();

        $viewMock = m::mock(\Application\View\View::class);

        $container = new \Test\Decorator\ContainerDecorator(
            $serviceContainerMockBuilder->build(),
            $contextMock,
            $viewMock
        );

        $container();

        /** @var \Application\Response\ResponseTypes\RedirectResponse $response */
        $response = $container->getResults();

        $this->assertInstanceOf(\Application\Response\ResponseTypes\RedirectResponse::class, $response);
        $this->assertEquals(['Location: /admin/index'], $response->getHeaders());
    }

    /**
     * @throws \Application\Router\RouteException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Application\View\ViewException
     * @throws \Response\ResponseTypes\RedirectResponseException
     */
    public function testShouldReturnErrorResponse()
    {
        $serviceContainerMockBuilder = $this->getServiceContainerMockBuilder();
        $serviceContainerMockBuilder->setAccessCheckerMock(
            m::mock(\Application\Service\AccessChecker\AccessChecker::class)
                ->shouldReceive('hasAccess')
                ->twice()
                ->andReturn(true)
                ->getMock()
        );
        $contextMock = m::mock(\Application\Context\Context::class)
            ->shouldReceive('__invoke')
            ->andThrow(\Application\Router\RouteException::class, 'Route \'/a/s/d/1\' not found')
            ->getMock();

        $viewMock = m::mock(\Application\View\View::class)
            ->shouldReceive('render')
            ->withArgs(function ($template, $vars){
                return (($vars['exception'] instanceof \Application\Router\RouteException) && $vars['exception']->getMessage() === 'Route \'/a/s/d/1\' not found');
            })
            ->andReturn('error: Route \'/a/s/d/1\' not found')
            ->getMock();

        $container = new \Test\Decorator\ContainerDecorator(
            $serviceContainerMockBuilder->build(),
            $contextMock,
            $viewMock
        );

        $container();

        /** @var \Application\Response\ResponseTypes\RedirectResponse $response */
        $response = $container->getResults();

        $this->assertInstanceOf(\Application\Response\ResponseTypes\ErrorResponse::class, $response);
        $this->assertInstanceOf(\Application\Router\RouteException::class, $response->getParameters()['exception']);
        $this->assertEquals('Route \'/a/s/d/1\' not found', $response->getParameters()['exception']->getMessage());
    }
}