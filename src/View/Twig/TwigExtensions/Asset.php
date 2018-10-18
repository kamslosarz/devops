<?php

namespace Application\View\Twig\TwigExtensions;

use Application\Config\Config;

class Asset extends Extension implements \Twig_Extension_GlobalsInterface
{
    public function getGlobals()
    {
        return [
            'asset' => $this
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_Function('asset', [$this, 'asset'])
        ];
    }

    public function asset($asset)
    {
        return sprintf('/assets/%s', str_replace('assets/', '', $asset));
    }
}