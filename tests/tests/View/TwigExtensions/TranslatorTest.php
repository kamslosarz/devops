<?php

namespace tests\View\TwigExtensions;

use Application\View\Twig\TwigExtensions\Messages;
use Application\View\Twig\TwigExtensions\Service;
use Application\View\Twig\TwigExtensions\Translator;
use function foo\func;
use Test\TestCase\TwigExtensionTestCase;
use Mockery as m;


class TranslatorTest extends TwigExtensionTestCase
{
    function testShouldGetGlobals()
    {
        $serviceContainerMock = $this->getServiceContainerMockBuilder()->build();
        $extension = new Translator($serviceContainerMock);
        $globals = $extension->getGlobals();

        $this->assertEmpty($globals);
    }

    function testShouldGetFilters()
    {
        $serviceContainerMock = $this->getServiceContainerMockBuilder()->build();
        $translator = new Translator($serviceContainerMock);
        $filters = $translator->getFilters();

        $this->assertEquals('trans', $filters[0]->getName());
    }

    function testShouldGetFunctions()
    {
        $reflection = new \ReflectionClass($this);
        $this->assertFalse($reflection->hasMethod('getFunctions'));
    }

    function testShouldTranslateResource()
    {
        $serviceContainerMockBuilder = $this->getServiceContainerMockBuilder();
        $serviceContainerMockBuilder->setTranslatorMock(
            m::mock(\Application\Service\Translator\Translator::class)
                ->shouldReceive('translate')
                ->withArgs(['test-with-variables', ['two' => 2]])
                ->andReturnUsing(function ($phrase, $vars)
                {
                    return [$phrase, $vars];
                })
                ->getMock()
        );

        $translator = new Translator($serviceContainerMockBuilder->build());
        $results = $translator->translate('test-with-variables', ['two' => 2]);
        $this->assertEquals(['test-with-variables', ['two' => 2]], $results);
    }
}