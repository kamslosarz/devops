<?php

namespace Test\TestCase;


use Application\Config\Config;
use PHPUnit\Framework\TestCase;

abstract class ViewTestCase extends TestCase
{
    public function getTwig(): \Twig_Environment
    {
        $config = $this->getConfig();
        $loader = new \Twig_Loader_Filesystem($config['loader']['templates']);

        return new \Twig_Environment($loader, [
            'cache' => $config['loader']['cache'],
            'debug' => true,
        ]);
    }

    public function getDom($html): \DOMDocument
    {
        $dom = new \DOMDocument($html);
        $dom->loadHTML($html);

        return $dom;
    }

    public function getConfig(): array
    {
        return (include FIXTURE_DIR . '/config/configTest.php')['twig'];
    }
}