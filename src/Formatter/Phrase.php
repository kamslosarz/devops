<?php

namespace Application\Formatter;

class Phrase implements PhraseInterface
{
    private $phrase;
    private $variables;

    public function __construct($phrase)
    {
        $this->phrase = $phrase;
    }

    public function setVariables($variables): self
    {
        $this->variables = $variables;

        return $this;
    }

    private function applyVariables(): string
    {
        return str_replace(array_map(function ($item){

            return sprintf('%%%s%%', $item);
        }, array_keys($this->variables)), array_values($this->variables), $this->phrase);
    }

    public function __toString() : string
    {
        if($this->hasVariables())
        {
            return $this->applyVariables();
        }
        else
        {
            return $this->phrase;
        }
    }

    private function hasVariables(): bool
    {
        return !empty($this->variables);
    }
}