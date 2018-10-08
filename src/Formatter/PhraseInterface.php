<?php

namespace Application\Formatter;

interface PhraseInterface
{
    public function __construct($phrase);

    public function setVariables($variables);

    public function __toString();
}