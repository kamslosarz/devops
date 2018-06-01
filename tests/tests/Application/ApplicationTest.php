<?php

class ApplicationTest extends \Test\TestCase\ControllerTestCase
{
    public function testShouldThrowApplicationException()
    {
        $this->expectExceptionMessage('Route \'/asdasdas\' not found');
        $this->expectException(\Application\Router\RouteException::class);

        $dispatcher = $this->getApplicationContainer();
        $dispatcher->dispatch('/asdasdas');
    }

    public function testShouldInvokeApplicationInstance()
    {
        $dispatcher = $this->getApplicationContainer();
        $results = $dispatcher->dispatch('/admin/login');

        $crawler = $this->getCrawler($results);

        $this->assertEquals('login-form',
            $crawler->filterXPath('//body/div/div[@class="main-content"]/div[@class="content"]/form[@class="login-form"]')->attr('class')
        );
    }

    public function getDataSet()
    {
        return parent::getUserDataSet();
    }

}