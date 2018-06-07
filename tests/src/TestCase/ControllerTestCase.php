<?php

namespace Test\TestCase;

use Application\Service\Appender\Appender;
use Application\Service\AuthService\AuthService;
use Mockery as m;
use PHPUnit\DbUnit\DataSet\ArrayDataSet;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Test\ApplicationContainer\ApplicationContainer;
use Test\TestCase\Traits\DatabaseTestCaseTrait;
use Test\TestCase\Traits\ServiceContainerMockBuilderTrait;

abstract class ControllerTestCase extends TestCase
{
    use TestCaseTrait;
    use DatabaseTestCaseTrait;
    use ServiceContainerMockBuilderTrait;

    /**
     * /**
     * @return m\MockInterface
     */
    public function getAppenderMock()
    {
        return m::mock(Appender::class);
    }

    public function getApplicationContainer($logged = true)
    {
        $app = new ApplicationContainer();

        if($logged)
        {
            $app->getRequest()->setSession(AuthService::AUTH_KEY_NAME, $this->getUser()->getUserAuthTokens()->getFirst()->getToken());
        }

        return $app;
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
        return new ArrayDataSet([
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
            ],
            'users_privileges' => [
                [
                    'id' => 1,
                    'user_id' => 1,
                    'name' => 'Admin\UserController:login'
                ], [
                    'id' => 2,
                    'user_id' => 1,
                    'name' => 'Admin\UserController:logout'
                ], [
                    'id' => 3,
                    'user_id' => 1,
                    'name' => 'Admin\ProjectController:index'
                ], [
                    'id' => 5,
                    'user_id' => 1,
                    'name' => 'Admin\ProjectController:project'
                ], [
                    'id' => 6,
                    'user_id' => 1,
                    'name' => 'Admin\AdminController:index'
                ]
            ]
        ]);
    }

    public function setUp()
    {
        $return = parent::setUp();

        unset($_SESSION);
        unset($_COOKIE);

        return $return;
    }

    public function tearDown()
    {
        $return = parent::setUp();

        unset($_SESSION);
        unset($_COOKIE);

        return $return;
    }

}