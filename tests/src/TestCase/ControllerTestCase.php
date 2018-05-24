<?php

namespace Test\TestCase;

use Application\Container\Appender\Appender;
use Application\Container\Container;
use Application\Router\Route;
use Application\Service\AuthService\AuthService;
use Application\Service\Session\Session;
use Mockery as m;
use PHPUnit\DbUnit\DataSet\ArrayDataSet;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Test\ControllerDispatcher\ControllerDispatcher;
use Test\TestCase\Traits\DatabaseTestCaseTrait;

abstract class ControllerTestCase extends TestCase
{
    use TestCaseTrait;
    use DatabaseTestCaseTrait;

    /**
     * @return m\MockInterface
     */
    public function getAppenderMock()
    {
        return m::mock(Appender::class);
    }

    public function getDispatcher($logged = true)
    {
        $controllerDispatcher = new ControllerDispatcher();

        if($logged)
        {
            $controllerDispatcher->getRequest()->setCookie(AuthService::AUTH_KEY_NAME, $this->getUser()->getUserAuthTokens()->getFirst()->getToken());
        }

        return $controllerDispatcher;
    }

    public function getCrawler($html)
    {
        return new Crawler($html);
    }

    public function getSeed($file)
    {
        return sprintf('%s/seed/%s', FIXTURE_DIR, $file);
    }

    /**
     * @return ArrayDataSet
     */
    public function getUserDataSet()
    {
        return new ArrayDataSet( [
            'users' => [
                [
                    'id' => 1,
                    'username' => 'testAdmin',
                    'password' => md5('testPassword')
                ]
            ],
            'users_auth_tokens' => [
                [
                    'id' => 1,
                    'user_id' => 1,
                    'token' => 'edc3d8b693144e3d62a3ac774c4da98c'
                ]
            ]
        ]);
    }

    /**
     * @return m\MockInterface
     */
    public function getContainerMock()
    {
        $sessionMock = m::mock(\Application\Service\Session\Session::class);
        $sessionMock->shouldReceive('save')
            ->once()
            ->getMock();

        $routeMock = m::mock(Route::class);
        $routeMock->shouldReceive('getAccess')
            ->andReturns(Route::ACCESS_PUBLIC);

        $authServiceMock = m::mock(\Application\Service\AuthService\AuthService::class);
        $authServiceMock->shouldReceive('hasAccess')
            ->once()
            ->andReturns(false)
            ->getMock()
            ->shouldReceive('getSession')
            ->andReturns($sessionMock)
            ->getMock()
            ->shouldReceive('isAuthenticated')
            ->once()
            ->andReturns(true)
            ->shouldReceive('getRoute')
            ->once()
            ->andReturns($routeMock)
            ->getMock();

        $serviceContainerMock = m::mock(\Application\Service\ServiceContainer\ServiceContainer::class);
        $serviceContainerMock->shouldReceive('getService')
            ->andReturns($authServiceMock)
            ->once()
            ->getMock();

        $responseMock = m::mock(\Application\Response\Response::class);
        $responseMock->shouldReceive('setHeaders')
            ->once()
            ->getMock()->shouldReceive('__invoke')
            ->once()
            ->getMock();

        $routerMock = m::mock(\Application\Router\Router::class);
        $routerMock->shouldReceive('getRouteByParameters')
            ->once()
            ->andReturns('/admin/index')
            ->getMock();

        $contextMock = m::mock(\Application\Context\Context::class);
        $contextMock->shouldReceive('getRouter')
            ->once()
            ->andReturns($routerMock)
            ->getMock();

        return m::mock(Container::class)
            ->shouldReceive('getSession')
            ->andReturn(m::mock(Session::class))
            ->getMock()
            ->shouldReceive('getServiceContainer')
            ->andReturns($serviceContainerMock)
            ->once()
            ->getMock()
            ->shouldReceive('getResponse')
            ->once()
            ->andReturns($responseMock)
            ->getMock()
            ->shouldReceive('getContext')
            ->once()
            ->andReturns($contextMock)
            ->getMock();
    }
}