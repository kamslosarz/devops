<?php

namespace tests\Context;

use Application\Context\Context;
use Application\Response\Response;
use Application\Response\ResponseTypes\ErrorResponse;
use Application\Router\Route;
use Application\Router\RouteException;
use Application\Router\Router;
use Application\Service\AccessChecker\AccessChecker;
use Application\Service\Appender\Appender;
use Application\Service\Request\Request;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Test\TestCase\Traits\ServiceContainerMockBuilderTrait;

class ContextTest extends TestCase
{
    use ServiceContainerMockBuilderTrait;

    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function testShouldConstructContext()
    {
        $context = new Context($this->getServiceContainerMockBuilder()->build());

        $this->assertInstanceOf(Context::class, $context);
    }

    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function testShouldReturnNulledRouter()
    {
        $context = new Context($this->getServiceContainerMockBuilder()->build());

        $this->assertInstanceOf(Router::class, $context->getRouter());
    }

    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function testShouldReturnAppender()
    {
        $context = new Context($this->getServiceContainerMockBuilder()->build());

        $this->assertInstanceOf(Appender::class, $context->getAppender());
    }

    public function testShouldThrowRouteNotFoundException()
    {
        $this->expectException(RouteException::class);
        $this->expectExceptionMessage('Route \'/not-existing-route-to-nowhere\' not found');

        $serviceContainerMock = $this->getServiceContainerMockBuilder()
            ->setRequestMock($this->getRequestMock())
            ->setAccessCheckerMock($this->getAccessCheckerMock())
            ->build();

        /** @var ErrorResponse $response */
        $context = new Context($serviceContainerMock);
        $context();
        $context->getResults();
    }

    /**
     * @param $mockBuilder
     * @param $type
     * @param $contentClosure
     * @param $parameters
     * @param $headers
     * @throws \Application\Router\Dispatcher\DispatcherException
     * @throws \Application\Router\RouteException
     * @throws \Application\Service\AccessChecker\AccessDeniedException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Response\ResponseTypes\RedirectResponseException
     *
     * @dataProvider shouldReturnExpectedResponseType
     */
    public function testShouldReturnExpectedResponseType($mockBuilder, $type, $parameters, $headers)
    {
        /** @var ErrorResponse $response */
        $context = new Context($mockBuilder->build());
        $context();
        $response = $context->getResults();

        $this->assertInstanceOf($type, $response, 'invalid response type');
        $this->assertEquals($parameters, $response->getParameters(), 'invalid response parameters');
        $this->assertEquals($headers, $response->getHeaders(), 'invalid response headers');
    }

    public function shouldReturnExpectedResponseType()
    {
        return [
            'dataSet Response' => [
                $this->getServiceContainerMockBuilder()->setRequestMock(m::mock(Request::class)
                    ->shouldReceive('getRequestUri')
                    ->once()
                    ->andReturns('/admin/test/999/delete')
                    ->getMock()
                    ->shouldReceive('getRoute')
                    ->once()
                    ->andReturns(m::mock(Route::class))
                    ->getMock()
                    ->shouldReceive('setRoute')
                    ->once()
                    ->andReturns()
                    ->getMock()),
                Response::class,
                [999, 'delete'],
                []
            ],
            'dataSet RedirectResponse' => [
                $this->getServiceContainerMockBuilder()
                    ->setRequestMock(m::mock(Request::class)
                        ->shouldReceive('getRequestUri')
                        ->once()
                        ->andReturns('/admin/logout')
                        ->getMock()
                        ->shouldReceive('getRoute')
                        ->once()
                        ->andReturns(m::mock(Route::class))
                        ->getMock()
                        ->shouldReceive('setRoute')
                        ->once()
                        ->andReturns()
                        ->getMock()
                    )->setAuthServiceMock(
                        $this->getServiceContainerMockBuilder()
                            ->getAuthServiceMock()
                            ->shouldReceive('clearSession')
                            ->once()
                            ->andReturnSelf()
                            ->getMock()
                    )->setSessionMock(
                        $this->getServiceContainerMockBuilder()
                            ->getSessionMock()
                            ->shouldReceive('set')
                            ->andReturnSelf()
                            ->getMock()
                    ),
                Response::class,
                null,
                ['Location: /admin/login']
            ],
            'dataSet JsonResponse' => [
                $this->getServiceContainerMockBuilder()
                    ->setRequestMock(m::mock(Request::class)
                        ->shouldReceive('getRequestUri')
                        ->once()
                        ->andReturns('/admin/logout')
                        ->getMock()
                        ->shouldReceive('getRoute')
                        ->once()
                        ->andReturns(m::mock(Route::class))
                        ->getMock()
                        ->shouldReceive('setRoute')
                        ->once()
                        ->andReturns()
                        ->getMock()
                    )->setAuthServiceMock(
                        $this->getServiceContainerMockBuilder()
                            ->getAuthServiceMock()
                            ->shouldReceive('clearSession')
                            ->once()
                            ->andReturnSelf()
                            ->getMock()
                    )->setSessionMock(
                        $this->getServiceContainerMockBuilder()
                            ->getSessionMock()
                            ->shouldReceive('set')
                            ->andReturnSelf()
                            ->getMock()
                    ),
                Response::class,
                null,
                ['Location: /admin/login']
            ]
        ];

    }

    private function getAccessCheckerMock()
    {
        return m::mock(AccessChecker::class)
            ->shouldReceive('hasAccess')
            ->andReturns(true)
            ->getMock();
    }

    private function getRequestMock()
    {
        return m::mock(Request::class)
            ->shouldReceive('getRoute')
            ->once()
            ->andReturns($this->getServiceContainerMockBuilder()->getRouteMock())
            ->getMock()
            ->shouldReceive('setRoute')
            ->once()
            ->andReturns()
            ->getMock()
            ->shouldReceive('getRequestUri')
            ->once()
            ->andReturn('/not-existing-route-to-nowhere')
            ->getMock();
    }
}