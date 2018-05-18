<?php

namespace Test\TestCase;

use Application\Config\Config;
use Application\Container\Appender\Appender;
use Application\Container\Container;
use Application\Router\Route;
use Application\Service\Session\Session;
use Mockery as m;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Test\ControllerDispatcher\ControllerDispatcher;

abstract class ControllerTestCase extends TestCase
{
    use TestCaseTrait;

    // only instantiate pdo once for test clean-up/fixture load
    static private $pdo = null;

    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    private $conn = null;

    final public function getConnection()
    {
        if($this->conn === null)
        {
            if(self::$pdo == null)
            {
                self::$pdo = new \PDO(sprintf('sqlite::memory:', dirname(dirname(__DIR__))));
                self::$pdo->exec(file_get_contents(sprintf('%s/default.sql', FIXTURE_DIR)));
            }

            $this->conn = $this->createDefaultDBConnection(self::$pdo, ':memory:');
        }

        return $this->conn;
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

    /**
     * @return m\MockInterface
     */
    public function getAppenderMock()
    {
        return m::mock(Appender::class);
    }

    public function getDispatcher()
    {
        return new ControllerDispatcher();
    }

    public function getCrawler($html)
    {
        return new Crawler($html);
    }
}