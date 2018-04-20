<?php

namespace Application\View;

use Application\View\TwigExtensions\Asset;
use Application\View\TwigExtensions\Menu;

class TwigExtensionsMap
{
    const EXTENSIONS = [
        Menu::class,
        Asset::class
    ];
}