<?php

namespace Test\TestCase;

use Application\Config\Config;
use PHPUnit\Framework\TestCase;

abstract class ViewTestCase extends TestCase
{
    /**
     * @return \Twig_Environment
     */
    public function getTwig()
    {
        $config = Config::get('twig');
        $loader = new \Twig_Loader_Filesystem($config['loader']['templates']);

        return new \Twig_Environment($loader, [
            'cache' => $config['loader']['cache']
        ]);
    }

    /**
     * @param $html
     * @return \DOMDocument
     */
    public function getDom($html)
    {
        $dom = new \DOMDocument($html);
        $dom->loadHTML($html);

        return $dom;
    }
}