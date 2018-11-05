<?php

namespace tests\ParameterHolder;

use Application\ParameterHolder\ParameterHolder;
use PHPUnit\Framework\TestCase;

class ParameterHolderTest extends TestCase
{
    public function testShouldValidateParametersSet()
    {
        $parameters =
            [
                'test' => 'test',
                'test2' => [
                    'testtest'
                ]
            ];

        $parameterHolder = new ParameterHolder($parameters);
        $this->assertEquals($parameters, $parameterHolder->toArray());
    }

    public function testShouldAddParameters()
    {
        $parameterHolder = new ParameterHolder([
            'test' => 'test1'
        ]);

        $parameterHolder->add([
            'test2' => 'test3'
        ]);

        $this->assertEquals([
            'test' => 'test1',
            'test2' => 'test3'
        ], $parameterHolder->toArray());
    }

    public function testShouldTestOffsetGetSetExists()
    {
        $parameterHolder = new ParameterHolder();
        $this->assertFalse($parameterHolder->offsetExists('not-existing-offset'));
        $parameterHolder->offsetSet('test', 'test123');
        $this->assertTrue($parameterHolder->offsetExists('test'));
        $this->assertEquals('test123', $parameterHolder->offsetGet('test'));
        $parameterHolder->add([
            'test' => 321,
            'testtest' => 'testtest'
        ]);
        $this->assertEquals(2, $parameterHolder->count());
        $parameterHolder->offsetUnset('test');
        $this->assertFalse($parameterHolder->offsetExists('test'));
    }

    public function testShouldTestMagicMethods()
    {
        $parameterHolder = new ParameterHolder();
        $parameterHolder->test = 'test1234';
        $this->assertEquals('test1234', $parameterHolder->test);
        $this->assertEquals([
            'test' => 'test1234'
        ], $parameterHolder->toArray());
    }

    public function testShouldJsonSerializeParameters()
    {
        $parameterHolder = new ParameterHolder();
        $parameterHolder->test = 'test1234';
        $this->assertThat($parameterHolder->jsonSerialize(), self::equalTo(json_encode(['test' => 'test1234'])));
    }
}