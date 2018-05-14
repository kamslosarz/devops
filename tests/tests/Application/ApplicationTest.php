<?php

class ApplicationTest extends \Test\TestCase\ControllerTestCase
{
    public function testApplication()
    {
        $dispatcher = $this->getDispatcher();
        $results = $dispatcher->dispatch('/admin/login');

        $crawler = $this->getCrawler($results);


        $this->assertEquals('login-form',
            $crawler->filterXPath('//body/div/div[@class="main-content"]/div[@class="content"]/form[@class="login-form"]')->attr('class')

        );

    }
}