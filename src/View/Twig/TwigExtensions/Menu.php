<?php

namespace Application\View\Twig\TwigExtensions;

class Menu extends Extension implements \Twig_Extension_GlobalsInterface
{
    const ANY_PATTERN = '*';
    const ANY_REGEX = '[0-9a-zA-Z\/]{0,}';

    public function getGlobals(): array
    {
        return [
            'menu' => $this
        ];
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('url', [$this, 'url'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('isUri', [$this, 'isUri'], ['is_safe' => ['html']])
        ];
    }

    public function url($url, $class, $title): string
    {
        return sprintf('<a href="%s"><i class="%s"></i><p>%s</p></a>', $url, $class, $title);
    }

    public function isUri($uri): bool
    {
        if(!preg_match('/[\*]+/', $uri))
        {
            return $this->getService('request')->getRequestUri() === $uri;
        }

        return (1===preg_match(
            str_replace(self::ANY_PATTERN, self::ANY_REGEX, '/' . str_replace('/', '\/', $uri) . '/'),
            $this->getService('request')->getRequestUri()
        ));
    }
}