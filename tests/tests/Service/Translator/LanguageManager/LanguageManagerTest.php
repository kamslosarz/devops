<?php

namespace tests\Service\Translator\LanguageManager;

use Application\Formatter\Phrase;
use Application\Service\Translator\LanguageManager\Adapter\Adapter;
use Application\Service\Translator\LanguageManager\Adapter\FileAdapter;
use Application\Service\Translator\LanguageManager\LanguageManager;
use PHPUnit\Framework\TestCase;
use Mockery as m;


class LanguageManagerTest extends TestCase
{
    public function testShouldGetPhrase()
    {
        $adapterMock = m::mock(Adapter::class)
            ->shouldReceive('getResource')
            ->with('test-resource')
            ->andReturn('test resource value')
            ->once()
            ->getMock()->shouldReceive('hasResource')
            ->with('test-resource')
            ->andReturnTrue()
            ->once()
            ->getMock();

        $languageManager = new LanguageManager('en', [
            'adapter' => 'files',
            'path' => FIXTURE_DIR . '/langs'
        ]);

        $languageManager->setAdapter($adapterMock);
        $phrase = $languageManager->getPhrase('test-resource');

        $this->assertInstanceOf(Phrase::class, $phrase);
    }

}