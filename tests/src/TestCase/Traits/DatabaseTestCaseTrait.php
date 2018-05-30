<?php

namespace Test\TestCase\Traits;

use Model\User;
use Model\UserQuery;
use Propel\Runtime\Connection\PdoConnection;

trait DatabaseTestCaseTrait
{
    static private $pdo = null;
    private $conn = null;

    /** @var User */
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
     * @return User
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getUser()
    {
        $this->user = UserQuery::create()->findOneById(1);

        return $this->user;
    }

}