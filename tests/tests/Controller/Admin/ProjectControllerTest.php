<?php

class ProjectControllerTest extends \Test\TestCase\ControllerTestCase
{
    public function testIndexAction()
    {
        $results = $this->getDispatcher()->dispatch('/admin/project');
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

    public function getDataSet()
    {
        return $this->createFlatXMLDataSet($this->getSeed('projects.xml'));
    }
}