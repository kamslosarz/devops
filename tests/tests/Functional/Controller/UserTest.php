<?php

namespace tests\Functional\Controller;

use Application\Config\ConfigException;
use Test\TestCase\ControllerTestCase;
use Application\Config\Config;
use Test\TestCase\FunctionalTestCase;

class UserTest extends FunctionalTestCase
{
    public function testShouldRenderLoginAction()
    {
        $results = $this->getApplicationContainer(false)
            ->dispatch('/admin/login');

        $crawler = $this->getCrawler($results);
        $this->assertEquals('login-form',
            $crawler->filterXPath('//body/div/div[@class="main-content"]/div[@class="content"]/form[@class="login-form"]')->attr('class')
        );
    }


    public function testShouldLogoutAction()
    {
        $dispatcher = $this->getApplicationContainer();
        $results = $dispatcher->dispatch('/admin/logout');

        $this->assertNull($results);
        $this->assertEquals('Location: /admin/login', $dispatcher->getResponse()->getHeaders()[0]);
        $this->assertEquals('Successfully logged out', $_SESSION['messages']['SUCCESS']);
    }

    /**
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function testShouldExecuteLoginActionPostAndLoginUser()
    {
        $dispatcher = $this->getApplicationContainer(false);
        $dispatcher->getRequest()->setRequestMethod(\Application\Service\Request\RequestMethods::POST);
        $dispatcher->getRequest()->setPost('login', [
            'username' => 'testAdmin',
            'password' => 'testPassword'
        ]);

        $dispatcher->dispatch('/admin/login');

        $this->assertEquals(
            $_SESSION[\Application\Service\AuthService\AuthService::AUTH_KEY_NAME],
            $this->getUser()
                ->getUserAuthTokens(\Model\UserAuthTokenQuery::create()->limit(1)->orderByCreatedAt(\Propel\Runtime\ActiveQuery\ModelCriteria::DESC))
                ->getFirst()
                ->getToken()
        );

        $this->assertEquals($_SESSION['messages'], ['SUCCESS' => 'Successfully logged in']);
    }

    public function getDataSet()
    {
        return parent::getUserDataSet();
    }

    protected function setUp()
    {
        Config::set(Config::loadFlatFile(FIXTURE_DIR . '/testConfig.php'));

        parent::setUp();
    }
}