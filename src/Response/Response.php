<?php

namespace Application\Response;

use Application\Service\Router\Route;

class Response
{
    protected $code = 200;
    protected $headers = [];
    protected $type = ResponseTypes::HTML;
    protected $content = '';
    protected $parameters;
    protected $resource;

    public function __construct($resource, array $parameters = [])
    {
        $this->resource = $resource;
        $this->parameters = $parameters;
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    public function setHeaders(array $headers = []): self
    {
        $this->headers = $headers;

        return $this;
    }

    public function setCode($code): self
    {
        $this->code = $code;

        return $this;
    }

    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }

    public function setType($type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function __invoke(): void
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