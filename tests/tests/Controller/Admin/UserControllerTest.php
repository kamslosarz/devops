<?php

class UserControllerTest extends \Test\TestCase\ControllerTestCase
{
    public function testShouldRenderLoginAction()
    {
        $results = $this->getDispatcher(false)->dispatch('/admin/login');
        $crawler = $this->getCrawler($results);

        $this->assertEquals('login-form',
            $crawler->filterXPath('//body/div/div[@class="main-content"]/div[@class="content"]/form[@class="login-form"]')->attr('class')
        );
    }

    public function testShouldExecuteLoginActionPost()
    {
        $dispatcher = $this->getDispatcher(false);
        $dispatcher->getRequest()->setRequestMethod(\Application\Service\Request\RequestMethods::POST);
        $dispatcher->getRequest()->setPost('login', [
            'username' => 'testAdmin',
            'password' => 'testPassword'
        ]);

        $results = $dispatcher->dispatch('/admin/login');
        $crawler = $this->getCrawler($results);

        $this->assertEquals('Successfully logged in', $crawler->filterXPath('//div[@class="main-content"]/div[@class="messages"]/p[@class="success"]')->text());
        $this->assertEquals(
            $_SESSION[\Application\Service\AuthService\AuthService::AUTH_KEY_NAME],
            $this->getUser()->getUserAuthTokens(
                \Model\UserAuthTokenQuery::create()->limit(1)->orderByCreatedAt(\Propel\Runtime\ActiveQuery\ModelCriteria::DESC)
            )->getFirst()->getToken()
        );
    }

    public function testShouldRenderLogoutAction()
    {
        $dispatcher = $this->getDispatcher();
        $results = $dispatcher->dispatch('/admin/logout');

        $this->assertNull($results);
        $this->assertEquals('Location: /admin/login', $dispatcher->getResponse()->getHeaders()[0]);
        $this->assertEquals('Successfully logged out', $_SESSION['messages']['SUCCESS']);
    }

    public function getDataSet()
    {
        return parent::getUserDataSet();
    }

}