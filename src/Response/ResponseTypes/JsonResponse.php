<?php

namespace Application\Response\ResponseTypes;

use Application\Response\Response;
use Application\Response\ResponseTypes;

class JsonResponse extends Response
{
    public function __construct($parameters)
    {
        parent::__construct('', $parameters);

        $this->setType(ResponseTypes::JSON);
    }

    public function getJson(): string
    {
        return json_encode($this->parameters);
    }

    public function getContent(): string
    {
        return $this->getJson();
    }
}