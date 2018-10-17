<?php

namespace Application\Controller\Admin;

use Application\Controller\Controller;
use Application\Response\Response;
use Model\ProjectQuery;

class ProjectController extends Controller
{
    public function indexAction()
    {
        return new Response([
            'projects' => ProjectQuery::create()->find()
        ]);
    }

    public function projectAction()
    {
        return new Response();
    }
}