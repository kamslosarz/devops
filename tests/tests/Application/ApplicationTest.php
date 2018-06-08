<?php

class ApplicationTest extends \Test\TestCase\ControllerTestCase
{
    public function testShouldThrowRouteException()
    {
        $dispatcher = $this->getApplicationContainer();
        $dispatcher->dispatch('/asdasdas');

        $this->assertEquals('ERROR Route &#039;/asdasdas&#039; not found', $dispatcher->getResponse()->getContent());
    }

    public function testShouldInvokeApplicationInstanceAndReturnLoginForm()
    {
        $dispatcher = $this->getApplicationContainer(false);
        $results = $dispatcher->dispatch('/admin/login');
        $crawler = $this->getCrawler($results);

        $this->assertEquals('login-form',
            $crawler->filterXPath('//body/div/div[@class="main-content"]/div[@class="content"]/form[@class="login-form"]')->attr('class')
        );
    }

    public function testShouldInvokeApplicationInstanceAndReturnAdminDashboard()
    {
        $dispatcher = $this->getApplicationContainer();
        $results = $dispatcher->dispatch('/admin/index');
        $crawler = $this->getCrawler($results);

        $this->assertEquals('Dashboard', trim($crawler->filterXPath('//body/div/div[@class="main-content"]/div[@class="content"]')->text()));
    }

    public function getDataSet()
    {
        return parent::getUserDataSet();
    }

}