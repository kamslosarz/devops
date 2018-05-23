<?php

class UserControllerTest extends \Test\TestCase\ControllerTestCase
{
    public function testLoginAction()
    {
        $results = $this->getDispatcher(false)->dispatch('/admin/login');
        $crawler = $this->getCrawler($results);

        $this->assertEquals('login-form',
            $crawler->filterXPath('//body/div/div[@class="main-content"]/div[@class="content"]/form[@class="login-form"]')->attr('class')
        );
    }

    public function testLoginActionPost()
    {
        $dispatcher = $this->getDispatcher();
        $dispatcher->getRequest()->setRequestMethod(\Application\Service\Request\RequestMethods::POST);
        $dispatcher->getRequest()->setPost('login', [
            'username' => 'testAdmin',
            'password' => 'testPassword'
        ]);

        $results = $dispatcher->dispatch('/admin/login');
        $crawler = $this->getCrawler($results);

        $this->assertEquals('Successfully logged in', $crawler->filterXPath('//div[@class="main-content"]/div[@class="messages"]/p[@class="success"]')->text());
        $this->assertEquals($this->getAdminUser()->getUserAuthTokens()->getFirst()->getToken(), $dispatcher->getRequest()->getCookie(\Application\Service\AuthService\AuthService::AUTH_KEY_NAME));
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
            ]
        ]);
    }
}