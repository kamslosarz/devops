<?php

namespace tests\Service\Translator\LanguageManager\Adapter;

use Application\Service\Translator\LanguageManager\Adapter\FileAdapter;
use PHPUnit\Framework\TestCase;

class FileAdapterTest extends TestCase
{
    public function testShouldLoadLanguageResources()
    {
        $fileAdapter = new FileAdapter('en');

        $this->assertTrue($fileAdapter->hasResource('test-resource'));
        $this->assertEquals('test resource value', $fileAdapter->getResource('test-resource'));
    }
}