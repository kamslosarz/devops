<?php

namespace Application\Service\Translator\LanguageManager\Adapter;

use Application\Config\Config;
use Application\ParameterHolder\ParameterHolder;

class FileAdapter extends Adapter
{
    /** @var ParameterHolder $resources */
    private $resources;

    public function __construct($langCode)
    {
        $this->loadLanguageResources($langCode);
    }

    private function loadLanguageResources($langCode)
    {
        $this->resources = new ParameterHolder();

        /** @var \SplFileInfo $fileInfo */
        foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(Config::get('translator')['path']), \RecursiveIteratorIterator::SELF_FIRST) as $fileInfo)
        {
            if($fileInfo->isFile() && $fileInfo->getBasename() === sprintf('%s.php', $langCode) && $fileInfo->getExtension() === 'php')
            {
                $this->resources->add(include_once $fileInfo->getRealPath());
            }
        }
    }

    public function getResource($key)
    {
        return $this->resources->{$key};
    }

    public function hasResource($key)
    {
        return $this->resources->offsetExists($key);
    }
}