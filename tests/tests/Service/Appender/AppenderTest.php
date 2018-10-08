<?php

namespace tests\Service\Appender;

use Application\Service\Appender\Appender;
use Application\Service\Appender\AppenderLevel;
use Application\Service\Session\Session;
use Mockery as m;
use PHPUnit\Framework\TestCase;


class AppenderTest extends TestCase
{
    public function testShouldConstructAppender()
    {
        $sessionMock = m::mock(Session::class);
        $appender = new Appender($sessionMock);

        $this->assertInstanceOf(Appender::class, $appender);
    }

    public function testShouldAppendSession()
    {
        $message = 'test message';
        $level = AppenderLevel::SUCCESS;

        $sessionMock = m::mock(Session::class);
        $sessionMock->shouldReceive('set')
            ->once()
            ->withArgs([
                'messages', [
                    AppenderLevel::SUCCESS => [
                        $message,
                        'test success message',
                    ],
                    AppenderLevel::ERROR => [
                        'test error message'
                    ]
                ]
            ])
            ->andReturnSelf()
            ->getMock()
            ->shouldReceive('get')
            ->once()
            ->withArgs(['messages'])
            ->andReturns([
                AppenderLevel::SUCCESS => [
                    'test success message'
                ],
                AppenderLevel::ERROR => [
                    'test error message'
                ]
            ])
            ->getMock()
            ->shouldReceive('set')
            ->once()
            ->getMock()
            ->shouldReceive('get')
            ->once()
            ->withArgs(['messages'])
            ->andReturns([
                AppenderLevel::SUCCESS => [
                    $message,
                    'test success message',
                ],
                AppenderLevel::ERROR => [
                    'test error message'
                ]
            ]);

        $appender = new Appender($sessionMock);
        $appender->append($message, $level);

        $this->assertEquals([
            AppenderLevel::SUCCESS => [$message, 'test success message'],
            AppenderLevel::ERROR => ['test error message']
        ], $appender->flashMessages());
    }
}