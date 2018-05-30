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

        $this->assertArrayHasKey('/admin/login', $config);
    }

    public function testShouldThrowConfigException()
    {
        $filename = FIXTURE_DIR.DIRECTORY_SEPARATOR.'NOT_EXISTING_FILE.txt';
        $this->expectException(\Application\Config\ConfigException::class);
        $this->expectExceptionMessage(sprintf('File \'%s\' not exists', $filename));

        \Application\Config\Config::loadFlatFile($filename);
    }
}