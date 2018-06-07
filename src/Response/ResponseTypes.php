<?php

namespace Application\Response;


abstract class ResponseTypes
{
    const HTML = 'html';
    const JSON = 'json';
    const REDIRECT = 'redirect';
    const ERROR = 'error';
    const CONSOLE = 'console';
}