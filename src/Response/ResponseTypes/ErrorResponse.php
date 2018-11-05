<?php

namespace Application\Response\ResponseTypes;

use Application\Response\Response;
use Application\Response\ResponseTypes;

class ErrorResponse extends Response
{
    /**
     * ErrorResponse constructor.
     * @param array $parameters
     */
    public function __construct($parameters = [])
    {
        parent::__construct('error.html.twig', $parameters);

        $this->setType(ResponseTypes::ERROR);
    }
}