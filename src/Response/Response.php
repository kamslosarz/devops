<?php

namespace Application\Response;

class Response
{
    private $code;
    private $headers = [];
    private $type = ResponseTypes::CONTEXT_HTML;
    private $results;

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

    public function setResults($results)
    {
        $this->results = $results;

        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }


    public function getType()
    {
        return $this->type;
    }

    public function __invoke($force = false)
    {
        if(!headers_sent())
        {
            foreach($this->headers as $header)
            {
                header($header);
            }

            header("X-PHP-Response-Code: {$this->code}", true, $this->code);
        }

        return $this->results;
        if($force)
        {
            exit();
        }
    }

}