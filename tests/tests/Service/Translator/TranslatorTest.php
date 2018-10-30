<?php

namespace tests\Service\Translator;

use Application\Service\Cookie\Cookie;
use Application\Service\Request\Request;
use Application\Service\Translator\Translator;
use PHPUnit\Framework\TestCase;
use Mockery as m;
use Test\TestCase\Traits\ServiceContainerMockBuilderTrait;


class TranslatorTest extends TestCase
{
    use ServiceContainerMockBuilderTrait;

    public function testShouldReturnTranslatorService()
    {
        $serviceContainer = new \Application\Service\ServiceContainer\ServiceContainer($this->getServiceContainerConfig());
        $translator = $serviceContainer->getService('translator');

        $this->assertInstanceOf(Translator::class, $translator);
    }

    public function testShouldGetLanguageCodeFromCookie()
    {
        $cookieMock = m::mock(Cookie::class)
            ->shouldReceive('get')
            ->with(Translator::LANG_CODE_COOKIE)
            ->andReturn('pl')
            ->getMock()
            ->shouldReceive('set')
            ->withArgs([Translator::LANG_CODE_COOKIE, 'pl'])
            ->once()
            ->getMock();

        $this->assertEquals('pl', $this->getTranslator($this->getRequestMock()
            ->shouldReceive('getCookie')
            ->andReturn($cookieMock)
            ->getMock(),
            $this->getConfig())->getLanguageCode());

        $cookieMock->shouldHaveReceived('set');
    }

    public function testShouldGetLanguageCodeFromGlobals()
    {

        $cookieMock = m::mock(Cookie::class)
            ->shouldReceive('get')
            ->with(Translator::LANG_CODE_COOKIE)
            ->andReturnNull()
            ->getMock()
            ->shouldReceive('set')
            ->withArgs([Translator::LANG_CODE_COOKIE, 'pl'])
            ->once()
            ->getMock();

        $this->assertEquals('pl', $this->getTranslator(
            $this->getRequestMock()
                ->shouldReceive('getCookie')
                ->andReturn($cookieMock)
                ->getMock()
                ->shouldReceive('server')
                ->with('HTTP_ACCEPT_LANGUAGE')
                ->andReturn('pl,pl-PL,pl-PL;q=0.8,fr;q=0.6,pl-PL;q=0.4')
                ->getMock(),
            $this->getConfig())->getLanguageCode());

        $cookieMock->shouldHaveReceived('set');
    }

    private function getRequestMock()
    {
        return m::mock(Request::class);
    }

    private function getTranslator($requestMock, $config)
    {
        return new Translator($requestMock, $config);
    }

    public function getConfig()
    {
        return [
            'adapter' => 'files',
            'path' => FIXTURE_DIR . '/langs'
        ];
    }
}

