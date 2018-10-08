<?php

use Application\Console\Command\Command;
use Mockery as m;
use Model\User;
use Model\UserQuery;
use Test\TestCase\ConsoleTestCase;

class AdminCreateTest extends ConsoleTestCase
{
    /**
     * @dataProvider shouldValidateCommand
     * @throws \Application\Console\Command\CommandException
     */
    public function testShouldValidateCommand($username, $password)
    {
        $command = Command::getCommand((new \Application\Console\ConsoleParameters([
            '',
            'admin:create'
        ]))->getCommand());

        $commandParameterMock = m::mock(Command\CommandParameters::class)
            ->shouldReceive('toArray')
            ->andReturns([
                $username, $password
            ])
            ->getMock();

        $this->assertTrue($command->isValid($commandParameterMock));
    }

    /**
     * @throws \Application\Console\Command\CommandException
     */
    public function testShouldReturnInvalidUserDataError()
    {
        $command = Command::getCommand((new \Application\Console\ConsoleParameters([
            '',
            'admin:create'
        ]))->getCommand());

        $commandParameterMock = m::mock(Command\CommandParameters::class)
            ->shouldReceive('toArray')
            ->andReturns([
                'Admin', ''
            ])
            ->getMock();

        $this->assertFalse($command->isValid($commandParameterMock));
        $this->assertEquals($command->getErrors(), ['Invalid user data \'Admin\' \'\'']);
    }

    /**
     * @throws \Application\Console\Command\CommandException
     */
    public function testShouldReturnUserAlreadyExists()
    {
        $command = Command::getCommand((new \Application\Console\ConsoleParameters([
            '',
            'admin:create'
        ]))->getCommand());

        $commandParameterMock = m::mock(Command\CommandParameters::class)
            ->shouldReceive('toArray')
            ->andReturns([
                'testAdmin', 'password'
            ])
            ->getMock();

        $this->assertFalse($command->isValid($commandParameterMock));
        $this->assertEquals($command->getErrors(), ['User already exists']);
    }

    /**
     * @throws \Application\Console\Command\CommandException
     */
    public function testShouldCreateAdmin()
    {
        $username = 'TestUsername';
        $command = Command::getCommand((new \Application\Console\ConsoleParameters([
            '',
            'admin:create'
        ]))->getCommand());

        $commandParameterMock = m::mock(Command\CommandParameters::class)
            ->shouldReceive('toArray')
            ->andReturns([
                $username, 'test admin password'
            ])
            ->getMock();

        /** @var \Application\Response\Response $response */
        $response = $command->execute($commandParameterMock);

        $this->assertEquals('Admin created', $response->getContent());
        $this->assertInstanceOf(User::class, UserQuery::create()->findOneByUsername($username));
    }

    public function shouldValidateCommand()
    {
        return [
            'dataSet 1' => [
                'Admin username',
                'Admin password'
            ]
        ];
    }

    public function getDataSet()
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