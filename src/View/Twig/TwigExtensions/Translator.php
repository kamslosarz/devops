<?php

namespace Application\View\Twig\TwigExtensions;

use Twig\TwigFilter;

class Translator extends Extension implements \Twig_Extension_GlobalsInterface
{

    public function getGlobals()
    {
        return [];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('trans', [$this, 'translate'])
        ];
    }

    public function translate($phrase, $variables = [])
    {
        return $this->getService('translator')->translate($phrase, $variables);
    }
}