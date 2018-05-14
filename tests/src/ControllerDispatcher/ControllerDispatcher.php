<?php

namespace Test\ControllerDispatcher;

class ControllerDispatcher
{
    private $request;

    public function __construct()
    {
        $this->request = new RequestMock();
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setRequest($request)
    {
        $this->request = $request;

        return $this;
    }

    public function dispatch($route)
    {
        $this->getRequest()->setServer('REQUEST_URI', $route);
        $_SERVER = $this->request->getServer();
        $_POST = $this->request->getPost();
        $_GET = $this->request->getQuery();
        $content = '';
        ob_start();
        (new \Application\Application())();
        $content = ob_get_clean();
        ob_clean();

        return $content;
    }

}