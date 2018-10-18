<?php

namespace Application\View;

interface ViewElementInterface
{
    public function __construct($route, $parameters = []);
    public function getViewName();
    public function getParameters();
}