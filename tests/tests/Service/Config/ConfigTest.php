<?php

namespace tests\Service\Cookie;

use Application\Service\Config\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testShouldConstructConfig()
    {
        $config = new Config(['test-param' => 'test-val']);
        $this->assertThat($config->{'test-param'}, self::equalTo('test-val'));
    }
}