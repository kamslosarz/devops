<?php

namespace Application\Controller;


class IndexController extends Controller
{

    public function testAction($request){
        return [
            'test'=>'testAction'
        ];
    }

    public function indexAction($request)
    {
        return [
            'test' => 'indexAction'
        ];
    }

    public function testttAction($request)
    {
        return [
            'test' => 'testttAction'
        ];
    }
}