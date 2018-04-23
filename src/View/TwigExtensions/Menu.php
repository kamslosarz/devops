<?php

namespace Application\View\TwigExtensions;


use Application\Container\Container;

class Menu extends Extension implements \Twig_Extension_GlobalsInterface
{
    const ANY_PATTERN = '*';
    const ANY_REGEX = '[0-9a-zA-Z]+';

    public function getGlobals()
    {
        return [
            'menu' => $this
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('url', [$this, 'url']),
            new \Twig_SimpleFunction('isUri', [$this, 'isUri'])
        ];
    }

    public function url($url, $class, $title)
    {
        echo sprintf('<a href="%s"><i class="%s"></i><p>%s</p></a>', $url, $class, $title);
    }

    public function isUri($uri)
    {
        if (!preg_match('/[\*]+/', $uri)) {
            return $this->container->getServiceContainer()->getService('request')->requestUri() === $uri;
        }
        return preg_match(str_replace(self::ANY_PATTERN, self::ANY_REGEX, '/' . str_replace('/', '\/', $uri) . '/'), $this->container->getServiceContainer()->getService('request')->requestUri());
    }
}