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
        $templates = Config::get('twig')['loader']['templates'];

        if(!file_exists(sprintf('%s/%s', $templates, $asset)))
        {
            return $asset;
        }

        $fileContent = file_get_contents(sprintf('%s/%s', $templates, $asset));
        $assetFile = sprintf('%s/assets/%s', Config::get('web_dir'), str_replace('assets/', '', $asset));

        if(!file_exists($assetFile))
        {
            if(!is_dir(dirname($assetFile)))
            {
                mkdir(dirname($assetFile), 0777, true);
            }
            touch($assetFile);
        }

        file_put_contents($assetFile, $fileContent);

        return sprintf('/assets/%s', str_replace('assets/', '', $asset));
    }
}