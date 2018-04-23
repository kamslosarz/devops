<?php

namespace Application\View;

use Application\View\TwigExtensions\Asset;
use Application\View\TwigExtensions\Menu;
use Application\View\TwigExtensions\Messages;

class TwigExtensionsMap
{
    const EXTENSIONS = [
        Menu::class,
        Asset::class,
        Messages::class
    ];
}