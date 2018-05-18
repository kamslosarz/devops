<?php

class ProjectControllerTest extends \Test\TestCase\ControllerTestCase
{

    public function testIndexAction()
    {
        $projects = \Model\ProjectQuery::create()->find();

        $this->assertEquals(1, sizeof($projects->count()));
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