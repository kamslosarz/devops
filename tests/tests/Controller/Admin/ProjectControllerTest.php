<?php

class ProjectControllerTest extends \Test\TestCase\ControllerTestCase
{

    public function testIndexAction()
    {
        $projects = $this->getConnection()->getConnection()->exec('select * from projects');
        var_dump($projects);

        $projects = \Model\ProjectQuery::create()->find();
        var_dump($projects);

        exit;


        $this->assertEquals(1, sizeof($projects->fetchAll()));
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