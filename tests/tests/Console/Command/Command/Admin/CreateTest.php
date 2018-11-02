<?php

namespace tests\Console\Command\Command\Admin;

use Application\Console\Command\Command\Admin\Create;
use Application\Console\Command\CommandException;
use Application\Console\Command\CommandParameters;
use Application\EventManager\Event;
use Application\Response\ResponseTypes\ConsoleResponse;
use Application\Service\Router\Route;
use Application\Service\Router\Router;
use Application\Service\ServiceContainer\ServiceContainer;
use Model\User;
use Model\UserQuery;
use PHPUnit\DbUnit\DataSet\ArrayDataSet;
use Test\TestCase\DatabaseTestCase;
use Mockery as m;


class CreateTest extends DatabaseTestCase
{
    /**
     * @throws \Application\Console\Command\CommandException
     * @throws \Propel\Runtime\Exception\PropelException
     * @doesNotPerformAssertions
     */
    public function testShouldValidateCommandSuccess()
    {
        $event = m::mock(Event::class)
            ->shouldReceive('getParameters')
            ->andReturn(
                m::mock(CommandParameters::class)
                    ->shouldReceive('offsetExists')
                    ->withAnyArgs()
                    ->andReturnTrue()
                    ->getMock()
                    ->shouldReceive('offsetGet')
                    ->with('username')
                    ->andReturn('usernameTest')
                    ->getMock()
            )
            ->getMock();

        $create = new Create($event);

        $create->validate();
    }

    /**
     * @throws CommandException
     * @throws \Propel\Runtime\Exception\PropelException
     * @doesNotPerformAssertions
     */
    public function testShouldValidateFailed()
    {
        $this->expectException(CommandException::class);
        $this->expectExceptionMessage('User already exists');

        $event = m::mock(Event::class)
            ->shouldReceive('getParameters')
            ->andReturn(
                m::mock(CommandParameters::class)
                    ->shouldReceive('offsetExists')
                    ->withAnyArgs()
                    ->andReturnFalse()
                    ->getMock()
                    ->shouldReceive('offsetGet')
                    ->with('username')
                    ->andReturn('testUserToCreate')
                    ->getMock()
            )
            ->getMock();

        $create = new Create($event);

        $create->validate();
    }

    /**
     * @throws CommandException
     * @throws \Propel\Runtime\Exception\PropelException
     * @doesNotPerformAssertions
     */
    public function testShouldValidateForce()
    {
        $event = m::mock(Event::class)
            ->shouldReceive('getParameters')
            ->andReturn(
                m::mock(CommandParameters::class)
                    ->shouldReceive('offsetExists')
                    ->with('force')
                    ->andReturnTrue()
                    ->getMock()
            )
            ->getMock();

        $create = new Create($event);

        $create->validate();
    }


    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function testShouldExecuteCommandAndCreateUser()
    {
        $event = m::mock(Event::class)
            ->shouldReceive('getParameters')
            ->andReturn(m::mock(CommandParameters::class))
            ->getMock()
            ->shouldReceive('getServiceContainer')
            ->andReturn(
                m::mock(ServiceContainer::class)
                    ->shouldReceive('getService')
                    ->with('router')
                    ->andReturn(
                        m::mock(Router::class)
                            ->shouldReceive('getRoutes')
                            ->andReturn([
                                '/admin/index' => [],
                                '/user/edit/[id]' => [],
                            ])
                            ->getMock()
                    )
                    ->getMock()
            )
            ->getMock();

        $create = new Create($event);
        $consoleResponse = $create->execute('NewAdminUsername', 'newAdminpassword', false);

        $user = UserQuery::create()->findOneByusername('NewAdminUsername');


        $this->assertThat($consoleResponse, self::isInstanceOf(ConsoleResponse::class));
        $this->assertThat($consoleResponse->getContent(), self::equalTo('Admin created'));
        $this->assertThat($user, self::isInstanceOf(User::class));
        $this->assertThat($user->getUserPrivileges()->getColumnValues('Name'), self::equalTo([
            '/admin/index',
            '/user/edit/[id]'
        ]));
    }

    public function testShouldForceCreateUser()
    {
        $event = m::mock(Event::class)
            ->shouldReceive('getParameters')
            ->andReturn(m::mock(CommandParameters::class))
            ->getMock()
            ->shouldReceive('getServiceContainer')
            ->andReturn(
                m::mock(ServiceContainer::class)
                    ->shouldReceive('getService')
                    ->with('router')
                    ->andReturn(
                        m::mock(Router::class)
                            ->shouldReceive('getRoutes')
                            ->andReturn([
                                '/admin/index' => [],
                                '/user/edit/[id]' => [],
                            ])
                            ->getMock()
                    )
                    ->getMock()
            )
            ->getMock();

        $password = 'newUserToForceRecreatePassword';
        $create = new Create($event);
        $consoleResponse = $create->execute('userToForceRecreate', $password, true);
        $user = UserQuery::create()->findOneByusername('userToForceRecreate');

        $this->assertThat($consoleResponse->getContent(), self::equalTo('Admin created'));
        $this->assertThat($user->getusername(), self::equalTo('userToForceRecreate'));
        $this->assertThat($user->getPassword(), self::equalTo(md5($password)));
        $this->assertThat($user->getUserPrivileges()->getColumnValues('Name'), self::equalTo([
            '/admin/index',
            '/user/edit/[id]'
        ]));
    }

    public function getDataSet()
    {
        return new ArrayDataSet([
            'users' => [
                [
                    'id' => 999,
                    'username' => 'testUserToCreate',
                    'password' => md5('testPassword'),
                    'firstname' => 'test',
                    'lastname' => 'test',
                    'email' => 'test@test.pl'
                ], [
                    'id' => 998,
                    'username' => 'userToForceRecreate',
                    'password' => 'testPassword',
                    'firstname' => 'test',
                    'lastname' => 'test',
                    'email' => 'test@test.pl'
                ]
            ],
        ]);
    }
}