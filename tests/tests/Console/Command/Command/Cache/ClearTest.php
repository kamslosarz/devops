<?php

namespace tests\Console\Command\Command\Cache;

use Application\Config\Config;
use Application\Console\Command\Command\Cache\Clear;
use Application\Console\Command\Command\CommandParameters;
use PHPUnit\Framework\TestCase;
use Test\TestCase\Traits\ServiceContainerMockBuilderTrait;
use Mockery as m;

class ClearTest extends TestCase
{
    use ServiceContainerMockBuilderTrait;

    public function testShouldValidateCommand()
    {
        $clear = new Clear();
        $clear->setServiceContainer($this->getServiceContainerMockBuilder()->build());
        $this->assertTrue($clear->isValid(m::mock(CommandParameters::class)));
    }

    public function testShouldClearCache()
    {
        $clear = new Clear();
        $clear->setServiceContainer($this->getServiceContainerMockBuilder()->build());
        $testFile = Config::get('web_dir').'/assets/test-cached-file.css';
        $this->assertTrue(file_exists($testFile));
        $results = $clear->execute(m::mock(CommandParameters::class));
        $this->assertTrue($results);
        $this->assertFalse(file_exists($testFile));
    }

    protected function setUp()
    {
        exec(sprintf('chmod 777 %s -R', Config::get('web_dir')));
        Config::set(Config::loadFlatFile(FIXTURE_DIR . '/testConfig.php'));
        touch(Config::get('web_dir').'/assets/test-cached-file.css', 777);

        parent::setUp();
    }
}