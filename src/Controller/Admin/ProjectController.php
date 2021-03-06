<?php

namespace Application\Controller\Admin;

use Application\Controller\Controller;
use Application\Response\Response;
use Model\ProjectQuery;

class ProjectController extends Controller
{
    public function indexAction(): Response
    {
        return new Response('admin/project/index.html.twig', [
            'projects' => ProjectQuery::create()->find()
        ]);
    }

    public function projectAction(): Response
    {
        return new Response('admin/project/project.html.twig');
    }
}