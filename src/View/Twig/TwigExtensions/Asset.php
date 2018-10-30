<?php

namespace Application\View\Twig\TwigExtensions;


class Asset extends Extension implements \Twig_Extension_GlobalsInterface
{
    public function getGlobals(): array
    {
        return [
            'asset' => $this
        ];
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_Function('asset', [$this, 'asset'])
        ];
    }

    public function asset($asset): string
    {
        return sprintf('/assets/%s', str_replace('assets/', '', $asset));
    }
}