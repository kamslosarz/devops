<?php

use Application\Config\Config;

class ProjectTest extends \Test\TestCase\ControllerTestCase
{
    public function testShouldRenderIndexAction()
    {
        $dispatcher = $this->getApplicationContainer();
        $results = $dispatcher->dispatch('/admin/project');
        $crawler = $this->getCrawler($results);
        $table = $crawler->filterXPath('//table[@id="projects-list"]');

        $this->assertCount(10, $table->filterXPath('//tr'));
        $this->assertEquals([
            'test project name',
            'repository source'
        ], [
            trim($table->filterXPath('//tr[1]')->filterXPath('//td[1]')->text()),
            trim($table->filterXPath('//tr[1]')->filterXPath('//td[2]')->text())
        ]);
    }

    protected function getDataSet()
    {
        $dataSet = new \Test\Fixture\CompositeDataSet();
        $dataSet->addDataSet(parent::getUserDataSet());
        $dataSet->addDataSet($this->createFlatXMLDataSet($this->getSeed('projects.xml')));

        return $dataSet;
    }

    protected function setUp()
    {
        Config::set(Config::loadFlatFile(FIXTURE_DIR . '/controllersTestConfig.php'));

        parent::setUp();
    }
}