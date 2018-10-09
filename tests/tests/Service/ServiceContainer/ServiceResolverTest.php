<?php
/**
 * Created by PhpStorm.
 * User: kslosarz
 * Date: 09.10.18
 * Time: 11:59
 */

namespace tests\Service\ServiceContainer;

use Application\Service\ServiceContainer\ServiceResolver;
use Application\View\Twig\TwigExtensions\Service;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Test\Decorator\ServiceDecorator;

class ServiceResolverTest extends TestCase
{
    public function testShouldResolveService()
    {
        $serviceResolver = new ServiceResolver(ServiceDecorator::class, [['test' => 123]]);
        /** @var ServiceDecorator $serviceDecorator */
        $serviceDecorator = $serviceResolver();
        $this->assertInstanceOf(ServiceDecorator::class, $serviceDecorator);
        $this->assertEquals(['test'=>123], $serviceDecorator->getParameters());
    }
}