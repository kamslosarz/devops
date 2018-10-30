<?php

namespace tests\Service\Session;

use Application\Service\Request\Request;
use Application\Service\Router\Router;
use PHPUnit\Framework\TestCase;
use Test\TestCase\Traits\ServiceContainerMockBuilderTrait;
use Mockery as m;


class RouterTest extends TestCase
{
    use ServiceContainerMockBuilderTrait;

    public function testShouldGetUrl()
    {
        $request = m::mock(Request::class);
        $router = new Router($request, [
            '/test/[param]/action/[id]/12' => [\Test\Decorator\ControllerDecorator::class, 'eventManagerTestAction'],
        ]);

        $url = $router->getUrl('/test/[param]/action/[id]/12', [
            'param' => 'ParaAm',
            'id' => 9999
        ]);

        $this->assertThat($url, self::equalTo('/test/ParaAm/action/9999/12'));
    }
}