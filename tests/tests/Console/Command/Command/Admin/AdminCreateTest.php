<?php

use Application\Console\Command\Command;
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
        $command = Command::getInstance((new \Application\Console\ConsoleParameters([
            '',
            'admin:create'
        ]))->getCommand());

        $this->assertTrue($command->isValid($username, $password));
    }

    /**
     * @throws \Application\Console\Command\CommandException
     */
    public function testShouldReturnInvalidUserDataError()
    {
        $command = Command::getInstance((new \Application\Console\ConsoleParameters([
            '',
            'admin:create'
        ]))->getCommand());

        $this->assertFalse($command->isValid('Admin', ''));
        $this->assertEquals($command->getErrors(), ['Invalid user data \'Admin\' \'\'']);
    }

    /**
     * @throws \Application\Console\Command\CommandException
     */
    public function testShouldReturnUserAlreadyExists()
    {
        $command = Command::getInstance((new \Application\Console\ConsoleParameters([
            '',
            'admin:create'
        ]))->getCommand());

        $this->assertFalse($command->isValid('testAdmin', 'password'));
        $this->assertEquals($command->getErrors(), ['User already exists']);
    }

    /**
     * @throws \Application\Console\Command\CommandException
     */
    public function testShouldCreateAdmin()
    {
        $username = 'TestUsername';
        $command = Command::getInstance((new \Application\Console\ConsoleParameters([
            '',
            'admin:create'
        ]))->getCommand());

        /** @var \Application\Response\Response $response */
        $response = $command->execute($username, 'test admin password');

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
        return $this->createArrayDataSet([
            'users' => [
                [
                    'id' => 1,
                    'username' => 'testAdmin',
                    'password' => md5('testPassword')
                ]
            ],
        ]);
    }
}