<?php

use Application\Config\Config;

class AdminTest extends \Test\TestCase\FunctionalTestCase
{
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
        $results = $dispatcher->dispatch('/');
        $crawler = $this->getCrawler($results);

        $this->assertEquals('Dashboard', trim($crawler->filterXPath('//body/div/div[@class="main-content"]/div[@class="content"]')->text()));
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