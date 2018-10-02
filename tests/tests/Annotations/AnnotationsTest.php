<?php

namespace tests\Annotations;

use Application\Annotations\Annotations;
use Application\Annotations\Converter\Converter;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class AnnotationsTest extends TestCase
{

    /**
     * @dataProvider constructAnnotationsDataProvider
     * @param $docComment
     * @param array $expectedAnnotations
     */
    public function testShouldConstructAnnotations($docComment, array $expectedAnnotations)
    {
        $parameters = [
            m::mock('\ReflectionParameter')
                ->shouldReceive('getName')
                ->andReturn('user')
                ->getMock(),
            m::mock('\ReflectionParameter')
                ->shouldReceive('getName')
                ->andReturn('project')
                ->getMock(),
            m::mock('\ReflectionParameter')
                ->shouldReceive('getName')
                ->andReturn('secondProject')
                ->getMock()
        ];

        $reflection = m::mock('\ReflectionMethod')
            ->shouldReceive('getDocComment')
            ->andReturn($docComment)
            ->getMock()
            ->shouldReceive('getParameters')
            ->andReturn($parameters)
            ->getMock();


        $annotations = new Annotations($reflection, $parameters);

        $this->assertInstanceOf(Annotations::class, $annotations);

        foreach($annotations as $id => $annotation)
        {
            $this->assertInstanceOf($expectedAnnotations[$id], $annotations->getAnnotations()[0]);
        }
    }

    public function constructAnnotationsDataProvider()
    {
        return [
            'dataSet converter 1' => [
                '/**
                 * @convert(\'user\', options={"type":"Model", "class":"\Model\User"})
                 * @convert(\'user\', options={"type":"Model", "class":"\Model\User"})
                 * @param User $user
                 * @return Response
                 * @throws \Propel\Runtime\Exception\PropelException
                 */',
                [Converter::class, Converter::class]
            ],
            'dataSet converter 2' => [
                '/**
                 * @convert(\'project\', options={"type":"Model", "class":"\Model\Project", "test option":"option test"})
                 * @convert(\'secondProject\', options={"type":"Model", "class":"\Model\Project"})
                 * @param User $user
                 * @return Response
                 * @throws \Propel\Runtime\Exception\PropelException
                 */',
                [Converter::class, Converter::class]
            ],
        ];

    }
}
