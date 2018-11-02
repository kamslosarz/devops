<?php

namespace tests\Console\Command\Command\Cache;

use Application\Console\Command\Command\Cache\Build;
use Application\Console\Command\CommandException;
use Application\EventManager\Event;
use Application\ParameterHolder\ParameterHolder;
use Application\Service\Config\Config;
use Application\Service\ServiceContainer\ServiceContainer;
use PHPUnit\Framework\TestCase;
use Mockery as m;

class BuildTest extends TestCase
{
    /**
     * @throws \Application\Console\Command\CommandException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function testShouldExecuteSuccess()
    {
        $config = m::mock(Config::class);
        $config->twig = ['loader' => ['templates' => FIXTURE_DIR . '/resource']];
        $config->web_dir = FIXTURE_DIR . '/www';


        $event = m::mock(Event::class)
            ->shouldReceive('getServiceContainer')
            ->andReturn(
                m::mock(ServiceContainer::class)
                    ->shouldReceive('getService')
                    ->with('config')
                    ->andReturn($config)
                    ->getMock()
            )
            ->getMock()
            ->shouldReceive('getParameters')
            ->andReturn(m::mock(ParameterHolder::class))
            ->getMock();

        $build = new Build($event);

        $this->assertDirectoryNotExists(FIXTURE_DIR . '/www/assets');
        $consoleResponse = $build->execute();
        $this->assertDirectoryExists(FIXTURE_DIR . '/www/assets');
        $this->assertFileExists(sprintf('%s/www/assets/style-test.css', FIXTURE_DIR));
        $this->assertThat(
            trim($consoleResponse->getContent()),
            self::equalTo('Caching file /mnt/b4517e53-fc73-47a8-a901-625aa901c804/devops/tests/fixture/www/assets/style-test.css')
        );
    }

    /**
     * @throws \Application\Console\Command\CommandException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @doesNotPerformAssertions
     */
    public function testShouldThrowCannotCreateDirectoryException()
    {
        $this->expectException(CommandException::class);
        $this->expectExceptionMessage(sprintf('Cannot create dir directory "%s/protected-www/assets"', FIXTURE_DIR));

        $config = m::mock(Config::class);
        $config->twig = ['loader' => ['templates' => FIXTURE_DIR . '/resource']];
        $config->web_dir = FIXTURE_DIR . '/protected-www';

        mkdir(sprintf('%s/protected-www', FIXTURE_DIR), 0400, true);

        $event = m::mock(Event::class)
            ->shouldReceive('getServiceContainer')
            ->andReturn(
                m::mock(ServiceContainer::class)
                    ->shouldReceive('getService')
                    ->with('config')
                    ->andReturn($config)
                    ->getMock()
            )
            ->getMock()
            ->shouldReceive('getParameters')
            ->andReturn(m::mock(ParameterHolder::class))
            ->getMock();

        $build = new Build($event);
        $consoleResponse = $build->execute();
    }

    public function testShouldThrowDirectoryIsNotWritableException()
    {
        $this->expectException(CommandException::class);
        $this->expectExceptionMessage(sprintf('Directory "%s/public-www/assets" must be writable', FIXTURE_DIR));

        $config = m::mock(Config::class);
        $config->twig = ['loader' => ['templates' => FIXTURE_DIR . '/resource']];
        $config->web_dir = FIXTURE_DIR . '/public-www';

        mkdir(sprintf('%s/public-www/assets', FIXTURE_DIR), 0777, true);
        chmod(sprintf('%s/public-www/assets', FIXTURE_DIR), 0400);

        $event = m::mock(Event::class)
            ->shouldReceive('getServiceContainer')
            ->andReturn(
                m::mock(ServiceContainer::class)
                    ->shouldReceive('getService')
                    ->with('config')
                    ->andReturn($config)
                    ->getMock()
            )
            ->getMock()
            ->shouldReceive('getParameters')
            ->andReturn(m::mock(ParameterHolder::class))
            ->getMock();

        $build = new Build($event);
        $consoleResponse = $build->execute();
    }

    public function testShouldThrowCannotWriteToFileException()
    {
        $this->expectException(CommandException::class);
        $this->expectExceptionMessage(sprintf('Cannot write to file "%s/protected-assets-www/assets/style-test.css"', FIXTURE_DIR));

        $config = m::mock(Config::class);
        $config->twig = ['loader' => ['templates' => FIXTURE_DIR . '/resource/']];
        $config->web_dir = FIXTURE_DIR . '/protected-assets-www';

        mkdir(sprintf('%s/protected-assets-www/assets/', FIXTURE_DIR), 0777, true);
        touch(sprintf('%s/protected-assets-www/assets/style-test.css', FIXTURE_DIR));
        chmod(sprintf('%s/protected-assets-www/assets/style-test.css', FIXTURE_DIR), 0400);

        $event = m::mock(Event::class)
            ->shouldReceive('getServiceContainer')
            ->andReturn(
                m::mock(ServiceContainer::class)
                    ->shouldReceive('getService')
                    ->with('config')
                    ->andReturn($config)
                    ->getMock()
            )
            ->getMock()
            ->shouldReceive('getParameters')
            ->andReturn(m::mock(ParameterHolder::class))
            ->getMock();

        $build = new Build($event);
        $consoleResponse = $build->execute();
    }


    public function tearDown()
    {
        parent::tearDown();

        exec(sprintf('rm -rf %s/www/assets', FIXTURE_DIR));
        exec(sprintf('rm -rf %s/protected-www', FIXTURE_DIR));
        exec(sprintf('rm -rf %s/public-www', FIXTURE_DIR));
        exec(sprintf('rm -rf %s/protected-assets-www', FIXTURE_DIR));
    }
}