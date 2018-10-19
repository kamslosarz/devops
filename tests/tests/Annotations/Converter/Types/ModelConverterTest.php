<?php

namespace tests\Annotations\Converter\Types;


use Application\Annotations\Converter\Types\ModelConverter;
use Model\User;
use PHPUnit\DbUnit\DataSet\ArrayDataSet;
use Test\TestCase\DatabaseTestCase;

class ModelConverterTest extends DatabaseTestCase
{
    public function testShouldInvalidateConverter()
    {
        $options = new \stdClass();
        $options->type = 'Model';
        $options->class = 'Model\Base\NotExistingModelTest';

        $modelConverter = new ModelConverter($options);

        $this->assertFalse($modelConverter->isValid());
    }

    public function testShouldConvertModel()
    {
        $options = new \stdClass();
        $options->type = 'Model';
        $options->class = User::class;

        $modelConverter = new ModelConverter($options);
        $model = $modelConverter(123);

        $this->assertInstanceOf(User::class, $model);
    }

    /**
     * @return ArrayDataSet|\PHPUnit\DbUnit\DataSet\IDataSet
     */

    public function getDataSet()
    {
        return new ArrayDataSet([
            'users' => [
                [
                    'id' => 123,
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