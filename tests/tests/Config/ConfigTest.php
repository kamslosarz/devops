<?php

class ConfigTest extends \PHPUnit\Framework\TestCase
{
    public function testShouldGetConfigFromFlatFile()
    {
        $config = \Application\Config\Config::loadFlatFile(FIXTURE_DIR . DIRECTORY_SEPARATOR . 'config.php');

        $this->assertEquals(['test' => ['test' => 'test']], $config);
    }

    public function testShouldLoadConfigAndGetKey()
    {
        $config = \Application\Config\Config::get('routes');

        $this->assertArrayHasKey('app_admin_index', $config);
        $this->assertEquals('/admin/login', $config['app_admin_login']['url']);
    }

    public function testShouldThrowConfigException()
    {
        $filename = FIXTURE_DIR . DIRECTORY_SEPARATOR . 'NOT_EXISTING_FILE.txt';
        $this->expectException(\Application\Config\ConfigException::class);
        $this->expectExceptionMessage(sprintf('File \'%s\' not exists', $filename));

        \Application\Config\Config::loadFlatFile($filename);
    }

    /**
     * @param $env
     * @param $filename
     *
     * @dataProvider differentEnvironmentConfigsData
     */
    public function testShouldLoadDifferentEnvironmentConfigs($env, $config_file)
    {
        $config = include_once $config_file;

        \Application\Application::setEnvironment($env);

        $configLoaded = [];

        foreach($config as $key => $value)
        {
            $configLoaded[$key] = \Application\Config\Config::get($key);
        }

        $this->assertEquals($configLoaded, $config);
    }

    public function differentEnvironmentConfigsData()
    {
        return [
            'dataSet test' => ['_test', sprintf('%s/config/config_test.php', dirname(dirname(FIXTURE_DIR)))],
            'dataSet prod' => ['',sprintf('%s/config/config.php', dirname(dirname(FIXTURE_DIR)))]
        ];
    }
}