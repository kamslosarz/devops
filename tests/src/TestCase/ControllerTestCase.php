<?php

namespace Test\TestCase;

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

    public function setUp()
    {
        parent::setUp();

        self::$pdo = new \PDO(sprintf('sqlite:%s/test_devops.db3', FIXTURE_DIR));
        self::$pdo->exec(file_get_contents(sprintf('%s/default.sql', FIXTURE_DIR)));

        $this->conn = $this->createDefaultDBConnection(self::$pdo, 'test');

        $serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
        $serviceContainer->checkVersion('2.0.0-dev');
        $serviceContainer->setAdapterClass('default', 'sqlite');
        $manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
        $manager->setConfiguration(array(
            'dsn' => sprintf('sqlite:%s/test_devops.db3', FIXTURE_DIR),
            'user' => '',
            'password' => '',
            'settings' =>
                array(
                    'charset' => 'utf8',
                    'queries' =>
                        array(),
                ),
            'classname' => '\\Propel\\Runtime\\Connection\\ConnectionWrapper',
            'model_paths' =>
                array(
                    0 => 'src',
                    1 => 'vendor',
                ),
        ));
        $manager->setName('default');
        $serviceContainer->setConnectionManager('default', $manager);
        $serviceContainer->setDefaultDatasource('default');

    }

    final public function getConnection()
    {
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