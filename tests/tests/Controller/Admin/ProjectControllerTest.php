<?php

class ProjectControllerTest extends \Test\TestCase\ControllerTestCase
{
    public function testIndexAction()
    {
        $results = $this->getDispatcher()->dispatch('/admin/project');
        $crawler = $this->getCrawler($results);



        var_dump($crawler->text());

    }

    public function getDataSet()
    {
        return $this->createArrayDataSet([
            'projects' => [
                [
                    'id' => 1,
                    'name' => 'repository name',
                    'repository' => 'repository source'
                ]
            ]
        ]);
    }
}