<?php

namespace Application\View\Twig\TwigExtensions;


abstract class TwigExtensionsMap
{
    const EXTENSIONS = [
        Menu::class,
        Asset::class,
        Messages::class,
        Service::class,
        Translator::class
    ];
}