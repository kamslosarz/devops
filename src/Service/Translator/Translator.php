<?php

namespace Application\Service\Translator;

use Application\Factory\Factory;
use Application\Formatter\Phrase;
use Application\Service\Request\Request;
use Application\Service\ServiceInterface;
use Application\Service\Translator\LanguageManager\LanguageManager;

class Translator implements ServiceInterface
{
    const LANG_CODE_COOKIE = 'lang-code';

    private $request;
    private $languageCode;
    private $languageManager;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->setLanguageCode($this->getLanguageCodeCookie() ?? $this->getLanguageCodeFromRequest());
        $this->languageManager = Factory::getInstance(LanguageManager::class, [$this->languageCode]);
    }

    public function translate($phrase, $variables = [])
    {
        /** @var Phrase $phrase */
        $phrase = $this->languageManager->getPhrase($phrase);
        $phrase->setVariables($variables);

        return $phrase;
    }

    private function getLanguageCodeFromRequest()
    {
        return substr($this->request->server('HTTP_ACCEPT_LANGUAGE'), 0,2);
    }

    public function getLanguageCode()
    {
        return $this->languageCode;
    }

    public function setLanguageCode($code)
    {
        $this->languageCode = $code;

        return $this->setLanguageCodeCookie($code);
    }

    private function setLanguageCodeCookie($langCode)
    {
        $this->request->getCookie()->set(self::LANG_CODE_COOKIE, $langCode);

        return $this;
    }

    private function getLanguageCodeCookie()
    {
        return $this->request->getCookie()->get(self::LANG_CODE_COOKIE);
    }
}