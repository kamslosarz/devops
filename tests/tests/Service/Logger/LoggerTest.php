<?php

namespace tests\Service\Logger;

use Application\Application;
use Application\Service\Logger\Logger;
use Application\Service\Logger\LoggerException;
use Application\Service\Logger\LoggerLevel;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{

    public function tearDown()
    {
        exec(sprintf('rm -rf %s', FIXTURE_DIR . '/logs/*'));

        parent::tearDown();
    }

    public function testShouldLogMessage()
    {
        $logger = new Logger([
            'TestLogger' => [
                'dir' => FIXTURE_DIR . '/logs/',
                'name' => 'app'
            ],
        ]);

        Application::setEnvironment('tmp_prod');
        $logger->log('TestLogger', 'test message to log', LoggerLevel::INFO);
        Application::setEnvironment(Application::TEST);
        $filename = $logger->getFilename('TestLogger');
        $this->assertRegExp('/\[[0-9\-]+\]+ INFO: test message to log/', file_get_contents($filename));
    }

    public function testShouldThrowErrorWhenDirectoryIsNotWritable()
    {
        $this->expectException(LoggerException::class);
        $this->expectExceptionMessage(sprintf('Directory \'%s\' is not writable', FIXTURE_DIR . '/not_writable_dir'));

        $logger = new Logger([
            'TestLogger' => [
                'dir' => FIXTURE_DIR . '/not_writable_dir/',
                'name' => 'app'
            ],
        ]);

        Application::setEnvironment('tmp_prod');
        $logger->log('TestLogger', 'test message to log', LoggerLevel::INFO);
        Application::setEnvironment(Application::TEST);
    }
}