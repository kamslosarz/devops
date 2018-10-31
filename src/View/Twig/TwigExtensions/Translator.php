<?php

namespace Application\View\Twig\TwigExtensions;

use Application\ParameterHolder\Formatter\Phrase;
use Application\Service\ServiceContainer\ServiceContainer;
use Twig\TwigFilter;

class Translator extends Extension implements \Twig_Extension_GlobalsInterface
{
    /** @var \Application\Service\Translator\Translator $translator */
    protected $translator;

    public function __construct(ServiceContainer $serviceContainer)
    {
        parent::__construct($serviceContainer);

        $this->translator = $this->getService('translator');
    }

    public function getGlobals(): array
    {
        return [];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('trans', [$this, 'translate'])
        ];
    }

    public function translate($phrase, $variables = []): Phrase
    {
        return $this->translator->translate($phrase, $variables);
    }
}