<?php

namespace Application\Service\Translator\LanguageManager;

use Application\Factory\Factory;
use Application\ParameterHolder\Formatter\Phrase;
use Application\Service\Translator\LanguageManager\Adapter\Adapter;
use Application\Service\Translator\LanguageManager\Adapter\FileAdapter;

class LanguageManager
{
    const ADAPTERS_MAP = [
        'files' => FileAdapter::class
    ];

    /** @var Adapter $translationAdapter */
    private $adapter;

    public function __construct($languageCode, $config)
    {
        $this->adapter = Factory::getInstance($this->getAdapterClass($config['adapter']), [$languageCode, $config]);
    }

    public function setAdapter(Adapter $adapter): self
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * @param $phrase
     * @return Phrase
     */
    public function getPhrase($phrase): Phrase
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

    private function getAdapterClass($adapter): string
    {
        return self::ADAPTERS_MAP[$adapter];
    }
}