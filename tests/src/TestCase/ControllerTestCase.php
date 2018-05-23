<?php

namespace Test\TestCase;

use Application\Container\Appender\Appender;
use Application\Container\Container;
use Application\Router\Route;
use Application\Service\AuthService\AuthService;
use Application\Service\Session\Session;
use Mockery as m;
use Model\User;
use Model\UserAuthToken;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Connection\PdoConnection;
use Symfony\Component\DomCrawler\Crawler;
use Test\ControllerDispatcher\ControllerDispatcher;

abstract class ControllerTestCase extends TestCase
{
    use TestCaseTrait;

    static private $pdo = null;

    private $conn = null;

    private $user;

    /**
     * @return null|\PHPUnit\DbUnit\Database\DefaultConnection
     */
    final public function getConnection()
    {
        if($this->conn === null)
        {
            if(self::$pdo == null)
            {
                self::$pdo = new PdoConnection(
                    sprintf('sqlite::memory:', FIXTURE_DIR)
                );
                self::$pdo->exec(
                    file_get_contents(
                        sprintf('%s/default.sql', FIXTURE_DIR)
                    )
                );
            }

            $this->conn = $this->createDefaultDBConnection(self::$pdo);
            $manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
            $manager->setConnection($this->conn->getConnection());
            $manager->setName('default');
            $serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
            $serviceContainer->checkVersion('2.0.0-dev');
            $serviceContainer->setAdapterClass('default', 'sqlite');
            $serviceContainer->setConnectionManager('default', $manager);
            $serviceContainer->setDefaultDatasource('default');
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

    public function getDispatcher($logged = true)
    {
        $controllerDispatcher = new ControllerDispatcher();

        if($logged)
        {
            $controllerDispatcher->getRequest()->setCookie(AuthService::AUTH_KEY_NAME, $this->getAdminUser()->getUserAuthTokens()->getFirst()->getToken());
        }

        return $controllerDispatcher;
    }

    public function getCrawler($html)
    {
        return new Crawler($html);
    }

    /**
     * @return User
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getAdminUser()
    {
        if(!$this->user)
        {
            $this->user = new User();
            $this->user->setUsername('Admin');
            $this->user->setPassword(md5('aslknd08qh'));
            $this->user->save();
            $userAuthToken = new UserAuthToken();
            $userAuthToken->setToken(md5($this->user->getUsername() . $this->user->getPassword()));
            $this->user->addUserAuthToken($userAuthToken);
        }

        return $this->user;
    }

    public function getSeed($file)
    {
        return sprintf('%s/seed/%s', FIXTURE_DIR, $file);
    }

}