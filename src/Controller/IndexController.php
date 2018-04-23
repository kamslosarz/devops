<?php

namespace Application\Controller;


class IndexController extends Controller
{
    public function indexAction($request)
    {
        return [
            'test' => 'indexAction'
        ];
    }

}