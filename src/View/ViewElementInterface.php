<?php

namespace View;


interface ViewElementInterface
{
    public function __construct($viewName, $parameters = []);

}