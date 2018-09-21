<?php

namespace tests\Context;

use Application\Context\Context;
use Application\Response\Response;
use Application\Response\ResponseTypes\ErrorResponse;
use Application\Response\ResponseTypes\RedirectResponse;
use Application\Router\Route;
use Application\Router\RouteException;
use Application\Router\Router;
use Application\Service\AccessChecker\AccessChecker;
use Application\Service\Appender\Appender;
use Application\Service\Request\Request;
use Mockery as m;
use Model\Map\UserTableMap;
use Model\User;
use Model\UserQuery;
use PHPUnit\DbUnit\Database\DefaultConnection;
use PHPUnit\DbUnit\DataSet\ArrayDataSet;
use PHPUnit\DbUnit\TestCaseTrait;
use Propel\Runtime\Propel;
use Test\TestCase\Traits\DatabaseTestCaseTrait;
use Test\TestCase\Traits\ServiceContainerMockBuilderTrait;
use PHPUnit\Framework\TestCase;

class ContextTest extends TestCase
{
    use TestCaseTrait;
    use DatabaseTestCaseTrait;
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
     * @param $serviceContainerMock
     * @param $type
     * @param $parameters
     * @param $headers
     * @throws RouteException
     * @throws \Application\Router\Dispatcher\DispatcherException
     * @throws \Application\Service\AccessChecker\AccessDeniedException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function testShouldReturnResponse()
    {
        $serviceContainerMock = $this->getServiceContainerMockBuilder()
            ->setRequestMock(
                m::mock('Request')
                    ->shouldReceive('getRequestUri')
                    ->andReturn('/admin/user/999/delete')
                    ->getMock()
                    ->shouldReceive('getRoute')
                    ->andReturn()
                    ->getMock()
                    ->shouldReceive('setRoute')
                    ->andReturnSelf()
                    ->getMock()
                    ->shouldReceive('getRequestMethod')
                    ->once()
                    ->andReturns('GET')
                    ->getMock()
            );

        $serviceContainerMock = $serviceContainerMock->build();
        /** @var ErrorResponse $response */
        $context = new Context($serviceContainerMock);

        $context();
        $response = $context->getResults();

        /** @var m\MockInterface $appender */
        $appender = $context->getAppender();
        $appender->shouldHaveReceived('append')
            ->with('User was deleted', 'SUCCESS')
            ->once();

        $this->assertInstanceOf(Response::class, $response, 'invalid response type');
        $this->assertEquals([], $response->getHeaders(), 'invalid response headers');
    }

    /**
     * @throws RouteException
     * @throws \Application\Router\Dispatcher\DispatcherException
     * @throws \Application\Service\AccessChecker\AccessDeniedException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function testShouldReturnRedirectResponse()
    {
        $serviceContainerMock = $this->getServiceContainerMockBuilder()
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
                ->shouldReceive('getRequestMethod')
                ->once()
                ->andReturns('GET')
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
            );

        /** @var ErrorResponse $response */
        $context = new Context($serviceContainerMock->build());

        $context();
        $response = $context->getResults();

        $this->assertInstanceOf(RedirectResponse::class, $response, 'invalid response type');
        $this->assertNull($response->getParameters(), 'invalid response parameters');
        $this->assertEquals(['Location: /admin/login'], $response->getHeaders(), 'invalid response headers');
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
            ->shouldReceive('getRequestMethod')
            ->once()
            ->andReturns('GET')
            ->getMock()
            ->shouldReceive('getRequestUri')
            ->once()
            ->andReturn('/not-existing-route-to-nowhere')
            ->getMock();
    }

    public function getDataSet()
    {
        return new ArrayDataSet([
            'users' => [
                [
                    'id' => 999,
                    'username' => 'testAdmin',
                    'password' => md5('testPassword'),
                    'firstname' => 'test',
                    'lastname' => 'test',
                    'email' => 'test@test.pl'
                ]
            ],
        ]);
    }
}