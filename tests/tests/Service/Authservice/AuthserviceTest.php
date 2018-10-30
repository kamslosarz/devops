<?php

use Mockery as m;

class AuthServiceTests extends \Test\TestCase\DatabaseTestCase
{
    use \Test\TestCase\Traits\ServiceContainerMockBuilderTrait;

    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function testShouldReturnAuthService()
    {
        $serviceContainer = new \Application\Service\ServiceContainer\ServiceContainer($this->getServiceContainerConfig());
        $authService = $serviceContainer->getService('auth');

        $this->assertInstanceOf(\Application\Service\AuthService\AuthService::class, $authService);
    }

    public function testShouldAuthenticateUser()
    {
        $token = 'edc3d8b693144e3d62a3ac774c4da98c';

        $requestMock = m::mock(\Application\Service\Request\Request::class)
            ->shouldReceive('getSession')
            ->andReturn(
                m::mock(\Application\Service\Session\Session::class)
                    ->shouldReceive('get')
                    ->with(\Application\Service\AuthService\AuthService::AUTH_KEY_NAME)
                    ->andReturn($token)
                    ->getMock()
                    ->shouldReceive('set')
                    ->with(\Application\Service\AuthService\AuthService::AUTH_KEY_NAME, $token)
                    ->andReturnSelf()
                    ->getMock()
            )
            ->getMock();

        $authService = new \Application\Service\AuthService\AuthService($requestMock);
        $user = $authService->authenticate('testAdmin', 'testPassword');
        $this->assertInstanceOf(\Model\User::class, $user);
    }

    public function testShouldUnauthenticateUser()
    {
        $token = 'edc3d8b693144e3d62a3ac774c4da98c';

        $requestMock = m::mock(\Application\Service\Request\Request::class)
            ->shouldReceive('getSession')
            ->andReturn(
                m::mock(\Application\Service\Session\Session::class)
                    ->shouldReceive('get')
                    ->with(\Application\Service\AuthService\AuthService::AUTH_KEY_NAME)
                    ->andReturnFalse()
                    ->getMock()
                    ->shouldReceive('set')
                    ->with(\Application\Service\AuthService\AuthService::AUTH_KEY_NAME, $token)
                    ->andReturnSelf()
                    ->getMock()
            )
            ->getMock();

        $authService = new \Application\Service\AuthService\AuthService($requestMock);
        $user = $authService->authenticate('testAdmin', 'testPassword');
        $this->assertInstanceOf(\Model\User::class, $user);
    }

    protected function getDataSet()
    {
        return new \PHPUnit\DbUnit\DataSet\ArrayDataSet([
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
