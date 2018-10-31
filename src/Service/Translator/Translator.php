<?php

namespace Application\Service\Translator;

use Application\Factory\Factory;
use Application\ParameterHolder\Formatter\Phrase;
use Application\Service\Request\Request;
use Application\Service\ServiceInterface;
use Application\Service\Translator\LanguageManager\LanguageManager;

class Translator implements ServiceInterface
{
    const LANG_CODE_COOKIE = 'lang-code';

    private $request;
    private $languageCode;
    /** @var LanguageManager $languageManager*/
    private $languageManager;

    public function __construct(Request $request, $config)
    {
        $this->request = $request;
        $this->setLanguageCode($this->getLanguageCodeCookie() ?? $this->getLanguageCodeFromGlobals());
        $this->languageManager = Factory::getInstance(LanguageManager::class, [$this->languageCode, $config]);
    }

    public function translate($phrase, $variables = []): Phrase
    {
        /** @var Phrase $phrase */
        $phrase = $this->languageManager->getPhrase($phrase);
        $phrase->setVariables($variables);

        return $phrase;
    }

    public function getLanguageCode(): string
    {
        return $this->languageCode;
    }

    public function setLanguageCode($code): self
    {
        $this->languageCode = $code;

        return $this->setLanguageCodeCookie($code);
    }

    private function setLanguageCodeCookie($langCode): self
    {
        $this->request->getCookie()->set(self::LANG_CODE_COOKIE, $langCode);

        return $this;
    }

    private function getLanguageCodeCookie()
    {
        return $this->request->getCookie()->get(self::LANG_CODE_COOKIE);
    }

    private function getLanguageCodeFromGlobals()
    {
        return substr($this->request->server('HTTP_ACCEPT_LANGUAGE'), 0,2);
    }
}