<?php

namespace tests\Console\Command\Command\Cache;

use Application\Console\Command\Command\Cache\Clear;
use Application\Console\ConsoleParameters;
use Application\EventManager\Event;
use Application\Response\ResponseTypes\ConsoleResponse;
use Application\Service\Config\Config;
use Application\Service\ServiceContainer\ServiceContainer;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class ClearTest extends TestCase
{
    public function testShouldCreateCommandInstance()
    {
        $event = m::mock(Event::class)
            ->shouldReceive('getParameters')
            ->andReturn(
                m::mock(ConsoleParameters::class)
                    ->shouldReceive('toArray')
                    ->andReturn([])
                    ->getMock()
            )->getMock();

        $clear = new Clear($event);

        $this->assertThat($clear, self::isInstanceOf(Clear::class));
    }

    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function testShouldExecuteCommand()
    {
        $config = m::mock(Config::class);
        $config->web_dir = sprintf('%s/tmp-dir', FIXTURE_DIR);

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
            ->andReturn(
                m::mock(ConsoleParameters::class)
                    ->shouldReceive('toArray')
                    ->andReturn([])
                    ->getMock()
            )->getMock();

        $clear = m::mock(Clear::class, [$event])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('deleteCacheDirectory')
            ->with(sprintf('%s/tmp-dir', FIXTURE_DIR))
            ->once()
            ->getMock();

        /** @var ConsoleResponse $consoleResponse */
        $consoleResponse = $clear->execute();

        $this->assertThat($consoleResponse->getContent(), self::equalTo('Cache cleared'));
    }

    public function testShouldDeleteCacheDirectoryContent()
    {
        $dir = sprintf('%s/tmp-dir-with-assets', FIXTURE_DIR);
        $this->fillDirectory($dir);

        $config = m::mock(Config::class);
        $config->web_dir = $dir;

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
            ->andReturn(
                m::mock(ConsoleParameters::class)
                    ->shouldReceive('toArray')
                    ->andReturn([])
                    ->getMock()
            )->getMock();

        $clear = new Clear($event);

        $reflectionClass = new \ReflectionClass($clear);
        $reflectionMethod = $reflectionClass->getMethod('deleteCacheDirectory');
        $reflectionMethod->setAccessible(true);
        $reflectionMethod->invokeArgs($clear, [$dir]);

        $this->assertThat(glob($dir), self::equalTo([$dir]));
        rmdir($dir);
    }

    private function fillDirectory($dir)
    {
        $dir .= '/';
        for($x = 0; $x < 5; $x++)
        {
            $dir .= sprintf('test/', $x);

            for($y = 0; $y < 5; $y++)
            {
                @mkdir($dir, 0777, true);
                touch(sprintf('%ssome_file_%d_%d.css', $dir, $x, $y));
            }
        }
    }
}