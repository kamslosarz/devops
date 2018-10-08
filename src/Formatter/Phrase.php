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

    public function setPhrase($phrase)
    {
        $this->phrase = $phrase;

        return $this;
    }

    public function setVariables($variables)
    {
        $this->variables = $variables;

        return $this;
    }

    public function applyVariables()
    {
        return sprintf($this->phrase, $this->variables);
    }

    public function __toString()
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

    private function hasVariables()
    {
        return !empty($this->variables);
    }
}