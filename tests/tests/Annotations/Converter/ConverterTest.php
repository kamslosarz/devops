<?php

namespace tests\Annotations\Annotation;

use Application\Annotations\Converter\Converter;
use Application\Controller\Admin\UserController;
use Application\Router\Dispatcher\ControllerParameters;
use Model\User;
use PHPUnit\DbUnit\DataSet\ArrayDataSet;
use PHPUnit\DbUnit\DataSet\IDataSet;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;
use Mockery as m;
use Test\TestCase\Traits\DatabaseTestCaseTrait;

class ConverterTest extends TestCase
{
    use TestCaseTrait;
    use DatabaseTestCaseTrait;

    /**
     * @dataProvider shouldConvertParameter
     * @param $controllerClass
     * @param $name
     * @param $value
     * @param $options
     * @param $converted
     */
    public function testShouldConvertParameter($controllerClass, $name, $value, $options, $converted)
    {
        $controller = m::mock($controllerClass)
            ->makePartial();

        $controllerParameters = new ControllerParameters($controller, [$name => $value], 'editAction');

        $converter = new Converter($name, $value, $options);
        $converter->annotate($controllerParameters);

        $this->assertInstanceOf($converted, $controllerParameters->getParameter($name));
    }

    public function shouldConvertParameter()
    {
        return [
            'Test case Converter' => [
                UserController::class,
                'user',
                999,
                json_decode(json_encode([
                    'type' => 'Model',
                    'class' => User::class
                ])),
                User::class
            ]
        ];
    }

    public function getDataSet()
    {
        return new ArrayDataSet([
            'users' => [
                [
                    'id' => 999,
                    'username' => 'testAdmin',
                    'password' => md5('testPassword'),
                    'firstname' => 'test',
                    'lastname' => 'test',
                    'email' => 'test@test.pl'
                ]
            ],
        ]);
    }
}