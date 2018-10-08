<?php

namespace Test\TestCase;

use Application\Config\Config;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\SplFileInfo;
use Test\TestCase\Traits\ServiceContainerMockBuilderTrait;
use Mockery as m;

abstract class TwigExtensionTestCase extends TestCase
{
    const TMP_WEB_DIR = FIXTURE_DIR . '/tmp_web_dir';

    public function setUp()
    {
        Config::set([
            'twig' => [
                'loader' => [
                    'templates' => FIXTURE_DIR . '/resource',
                    'cache' => false
                ]
            ],
            'web_dir' => self::TMP_WEB_DIR
        ]);

        m::mock('mkdir');

        return parent::setUp();
    }

    public function tearDown()
    {
        exec(sprintf('rm -rf %s', self::TMP_WEB_DIR));

        parent::tearDown();
    }

    use ServiceContainerMockBuilderTrait;

    abstract function testShouldGetGlobals();

    abstract function testShouldGetFilters();

    abstract function testShouldGetFunctions();
}