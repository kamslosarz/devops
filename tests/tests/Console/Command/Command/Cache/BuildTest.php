<?php

namespace tests\Console\Command\Command\Cache;

use Application\Config\Config;
use Application\Console\Command\Command\Cache\Build;
use Application\Console\Command\Command\CommandParameters;
use Application\Console\Command\CommandException;
use Application\Response\ResponseTypes\ConsoleResponse;
use PHPUnit\Framework\TestCase;
use Test\TestCase\Traits\ServiceContainerMockBuilderTrait;
use Mockery as m;

class BuildTest extends TestCase
{
    const NOT_WRITABLE_DIR = FIXTURE_DIR . '/not-writable-dir';

    use ServiceContainerMockBuilderTrait;

    public function testShouldValidateCommandSuccess()
    {
        $command = new Build();
        $serviceContainerMockBuilder = $this->getServiceContainerMockBuilder();
        $command->setServiceContainer($serviceContainerMockBuilder->build());

        $this->assertTrue($command->isValid(m::mock(CommandParameters::class)));
    }

    public function testShouldCreateCachedAssetsSuccess()
    {
        $command = new Build();
        $serviceContainerMockBuilder = $this->getServiceContainerMockBuilder();
        $command->setServiceContainer($serviceContainerMockBuilder->build());
        /** @var ConsoleResponse $results */
        $results = $command->execute(m::mock(CommandParameters::class));
        $files = array_filter(explode('Caching file ', $results->getContent()), function ($element)
        {
            return !empty($element);
        });

        foreach($files as $cachedFile)
        {
            $this->assertFileExists(trim($cachedFile));
            $this->assertFileIsReadable(trim($cachedFile));
        }
    }

    /**
     * @throws CommandException
     */
    public function testShouldThrowExceptionWhenDestinationFileDirectoryIsNotWritable()
    {
        $this->expectException(CommandException::class);
        $this->expectExceptionMessageRegExp(sprintf('/Cannot create ".*" dir: ".*"/', self::NOT_WRITABLE_DIR));

        $this->loadNotWritableDirConfig();
        $this->makeNotWritableDirectory();

        $command = new Build();
        $command->setServiceContainer($this->getServiceContainerMockBuilder()->build());

        $results = $command->execute(
            m::mock(CommandParameters::class)
        );
    }

    /**
     * @throws CommandException
     */
    public function testShouldThrowExceptionWhenCannotWriteToFile()
    {
        $this->expectException(CommandException::class);
        $this->expectExceptionMessageRegExp('/Cannot write to file ".*"/');

        $this->setTestResources();
        $assetFile = Config::get('web_dir') . '/assets/style-test.css';
        touch($assetFile);
        exec(sprintf('chmod -R ugo-w %s', $assetFile));

        $command = new Build();
        $command->setServiceContainer($this->getServiceContainerMockBuilder()->build());

        $results = $command->execute(
            m::mock(CommandParameters::class)
        );
    }

    private function makeNotWritableDirectory()
    {
        mkdir(self::NOT_WRITABLE_DIR, 444);
        exec(sprintf('chmod -R ugo-w %s', self::NOT_WRITABLE_DIR));
    }

    private function deleteTmpDirectories()
    {
        if(file_exists(self::NOT_WRITABLE_DIR))
        {
            rmdir(self::NOT_WRITABLE_DIR);
        }

        exec(sprintf('rm -rf %s', FIXTURE_DIR . '/www/assets/*'));
    }

    private function loadNotWritableDirConfig()
    {
        $config = include FIXTURE_DIR . '/testConfig.php';
        $config['web_dir'] = self::NOT_WRITABLE_DIR;
        $config['twig']['loader']['templates'] = FIXTURE_DIR . '/resource';

        Config::set($config);
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->deleteTmpDirectories();
    }

    private function setTestResources()
    {
        $config = include FIXTURE_DIR . '/testConfig.php';
        $config['twig']['loader']['templates'] = FIXTURE_DIR . '/resource';
        Config::set($config);
    }

    protected function setUp()
    {
        exec(sprintf('chmod 777 %s -R', Config::get('web_dir')));
        Config::set(Config::loadFlatFile(FIXTURE_DIR . '/testConfig.php'));

        parent::setUp();
    }
}
