<?php

namespace tests\Controller;

use Application\Annotations\Annotations;
use Application\Router\Dispatcher\ControllerParameters;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Test\Decorator\ControllerDecorator;


class ControllerParametersTest extends TestCase
{
    public function testShouldOverrideParametersByOrder()
    {
        $controllerParameters = new ControllerParameters([
            'id' => 100,
            'test' => 100,
            'parameterName' => 'parameterName',
            'param5' => 'param 5'
        ]);
        $controllerParameters->addParameterToOverride('id', 500);
        $controllerParameters->addParameterToOverride('parameterName', 'parameterValue');
        $controllerParameters->overrideParameters([
            'param5',
            'parameterName',
            'test'
        ]);

        $this->assertEquals([
            'param5' => 'param 5',
            'parameterName' => 'parameterValue',
            'test' => 100,
            'id' => 500
        ], $controllerParameters->toArray());
    }

    public function testShouldOverrideParameters()
    {
        $controllerParameters = new ControllerParameters([
            'id' => 100,
            'test' => 100,
            'parameterName' => 'parameterName'
        ]);
        $controllerParameters->addParameterToOverride('id', 500);
        $controllerParameters->addParameterToOverride('parameterName', 'parameterValue');

        $this->assertEquals([
            'id' => 500,
            'parameterName' => 'parameterValue',
        ], $controllerParameters->getParametersToOverride());

        $controllerParameters->overrideParameters();

        $this->assertEquals([
            'id' => 500,
            'test' => 100,
            'parameterName' => 'parameterValue'
        ], $controllerParameters->toArray());
    }

    public function testShouldApplyAnnotations()
    {
        /** @var ControllerParameters $controllerParametersMock */
        $controllerParametersMock = m::mock(ControllerParameters::class)
            ->makePartial();
        $annotation = m::mock(Annotations::class)
            ->shouldReceive('annotate')
            ->andReturnUsing(function ($controllerParametersMock)
            {
                $controllerParametersMock->addParameterToOverride('user', 'override');
            })
            ->getMock();

        $controllerParametersMock
            ->shouldReceive('getAnnotations')
            ->andReturns(m::mock(Annotations::class)
                ->shouldReceive('getAnnotations')
                ->andReturns([$annotation])
                ->getMock()
                ->shouldReceive('getMethodParameterOrder')
                ->andReturns(['user'])
                ->getMock()
            )
            ->getMock();

        $controllerParametersMock->applyAnnotations(ControllerDecorator::class, 'testAction');

        $this->assertEquals('override', $controllerParametersMock->user);
        $annotation->shouldHaveReceived('annotate');
    }
}