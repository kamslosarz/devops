<?php

namespace Application\Response;


abstract class ResponseTypes
{
    const CONTEXT_HTML = 'html';
    const CONTEXT_JSON = 'json';
    const REDIRECT = 'redirect';
    const ERROR = 'error';
    const CONSOLE = 'console';
}