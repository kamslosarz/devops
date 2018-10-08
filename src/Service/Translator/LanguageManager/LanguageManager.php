<?php

namespace Application\Service\Translator\LanguageManager;

use Application\Config\Config;
use Application\Factory\Factory;
use Application\Formatter\Phrase;
use Application\Service\Translator\LanguageManager\Adapter\Adapter;
use Application\Service\Translator\LanguageManager\Adapter\FileAdapter;

class LanguageManager
{
    const ADAPTERS_MAP = [
        'files' => FileAdapter::class
    ];

    /** @var Adapter $translationAdapter */
    private $adapter;

    public function __construct($languageCode)
    {
        $this->adapter = Factory::getInstance($this->getAdapterClass(Config::get('translator')['adapter']), [$languageCode]);
    }

    /**
     * @param $phrase
     * @return Phrase
     */
    public function getPhrase($phrase)
    {
        if($this->adapter->hasResource($phrase))
        {
            return new Phrase($this->adapter->getResource($phrase));
        }
        else
        {
            return new Phrase($phrase);
        }
    }

    public function getAdapterClass($adapter)
    {
        return self::ADAPTERS_MAP[$adapter];
    }
}