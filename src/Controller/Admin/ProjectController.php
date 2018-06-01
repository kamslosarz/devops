<?php

namespace Application\Controller\Admin;

use Application\Container\Appender\AppenderLevel;
use Application\Model\User;
use Model\ProjectQuery;

class ProjectController extends Controller
{
    public function indexAction()
    {
        return [
            'projects' => ProjectQuery::create()->find()
        ];
    }

    public function projectAction($id)
    {
        return [];
    }
}