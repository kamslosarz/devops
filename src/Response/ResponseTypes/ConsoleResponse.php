<?php

namespace Application\Response\ResponseTypes;

use Application\Response\Response;
use Application\Response\ResponseTypes;

class ConsoleResponse extends Response
{
    /**
     * ErrorResponse constructor.
     * @param array $parameters
     */
    public function __construct($parameters = [])
    {
        parent::__construct($parameters);

        $this->setType(ResponseTypes::CONSOLE);
    }
}