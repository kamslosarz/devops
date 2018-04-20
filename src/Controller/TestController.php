<?php

namespace Application\Controller;



class TestController extends Controller
{
    public function indexAction($request)
    {
        $this->setContextJson();
        
        return [
            'status'=>'ok'
        ];
    }
}