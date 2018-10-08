<?php

namespace Application\Service\Translator\LanguageManager\Adapter;

abstract class Adapter
{
    abstract public function getResource($key);
    abstract public function hasResource($key);
}