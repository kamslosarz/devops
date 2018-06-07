<?php

namespace Application\Response;

use Application\Router\Route;

class Response
{
    protected $code = 200;
    protected $headers = [];
    protected $type = ResponseTypes::HTML;
    protected $content;
    protected $parameters;
    /** @var Route $route */
    protected $route;

    public function __construct($parameters = [])
    {
        $this->parameters = $parameters;
    }

    public function setHeaders(array $headers = [])
    {
        $this->headers = $headers;

        return $this;
    }

    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function setRoute($route)
    {
        $this->route = $route;

        return $this->route;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return Route
     */
    public function getRoute()
    {
        return $this->route;
    }

    public function __invoke()
    {
        if(!headers_sent())
        {
            foreach($this->headers as $header)
            {
                header($header, $this->code);
            }
        }

        echo $this->content;
    }
}