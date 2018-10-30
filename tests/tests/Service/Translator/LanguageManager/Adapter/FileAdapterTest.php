<?php

namespace tests\Service\Translator\LanguageManager\Adapter;

use Application\Service\Translator\LanguageManager\Adapter\FileAdapter;
use PHPUnit\Framework\TestCase;
use Test\TestCase\Traits\ServiceContainerMockBuilderTrait;

class FileAdapterTest extends TestCase
{
    use ServiceContainerMockBuilderTrait;

    public function testShouldLoadLanguageResources()
    {
        $fileAdapter = new FileAdapter('en', [
            'path' => FIXTURE_DIR . '/langs'
        ]);

        $this->assertTrue($fileAdapter->hasResource('test-resource'));
        $this->assertEquals('test resource value', $fileAdapter->getResource('test-resource'));
    }
}