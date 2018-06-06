<?php

namespace tests\Context;

use Application\Container\Appender\Appender;
use Application\Context\Context;
use Application\Response\Response;
use Application\Response\ResponseTypes\ErrorResponse;
use Application\Router\Route;
use Application\Router\Router;
use Application\Service\AccessChecker\AccessChecker;
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

    public function testShouldReturnErrorResponse()
    {
        $serviceContainerMock = $this->getServiceContainerMockBuilder()
            ->setRequestMock($this->getRequestMock())
            ->setAccessCheckerMock($this->getAccessCheckerMock())
            ->build();

        /** @var ErrorResponse $response */
        $response = (new Context($serviceContainerMock))();
        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->assertEquals('Route \'/not-existing-route-to-nowhere\' not found', $response->getParameters()['exception']->getMessage());
    }

    /**
     * @dataProvider  shouldReturnExpectedResponseType
     * @param $mockBuilder
     * @param $type
     * @param $contentClosure
     * @param $parameters
     * @param $headers
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function testShouldReturnExpectedResponseType($mockBuilder, $type, $contentClosure, $parameters, $headers)
    {
        /** @var ErrorResponse $response */
        $response = (new Context($mockBuilder->build()))();

        $this->assertInstanceOf($type, $response, 'invalid response type');
        $this->assertTrue($contentClosure($response->getContent()), sprintf('invalid response content: "%s"', $response->getContent()));
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
                function ($content)
                {
                    return (preg_match_all("/<html lang=\"[a-z]+\">(.*?)<\/html>/si", $content) > 0);
                },
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
                function ($content)
                {
                    return is_null($content);
                },
                null,
                ['Location: /admin/login']
            ],
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