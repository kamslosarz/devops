<?php

namespace Application\Service\Translator\LanguageManager\Adapter;

use Application\ParameterHolder\ParameterHolder;

class FileAdapter extends Adapter
{
    /** @var ParameterHolder $resources */
    private $resources;

    public function __construct($langCode, $config)
    {
        $this->loadLanguageResources($langCode, $config);
    }

    private function loadLanguageResources($langCode, $config): void
    {
        $this->resources = new ParameterHolder();

        /** @var \SplFileInfo $fileInfo */
        foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($config['path']), \RecursiveIteratorIterator::SELF_FIRST) as $fileInfo)
        {
            if($fileInfo->isFile() && $fileInfo->getBasename() === sprintf('%s.php', $langCode) && $fileInfo->getExtension() === 'php')
            {
                $this->resources->add(include_once $fileInfo->getRealPath());
            }
        }
    }

    public function getResource($key): string
    {
        return $this->resources->{$key};
    }

    public function hasResource($key): bool
    {
        return $this->resources->offsetExists($key);
    }
}