<?php

namespace tests\Console\Command;

use Application\Console\Command\CommandException;
use Application\Console\Command\CommandParameters;
use Application\Console\Command\CommandValidator;
use Application\EventManager\Event;
use Application\ParameterHolder\Constraint\Constraint;
use Application\Response\ResponseTypes\ConsoleResponse;
use PHPUnit\Framework\TestCase;
use Mockery as m;
use Test\Decorator\ConstraintDecorator;
use Test\Decorator\ConstraintDecoratorFailed;


class CommandValidatorTest extends TestCase
{
    public function testShouldConstructCommandValidator()
    {
        $event = m::mock(Event::class);
        $constraints = [
            ['username', Constraint::class, true]
        ];

        $commandValidator = new CommandValidator($event, $constraints);

        $this->assertThat($commandValidator, self::isInstanceOf(CommandValidator::class));
    }


    public function testShouldValidateSuccess()
    {
        $event = m::mock(Event::class)
            ->shouldReceive('getParameters')
            ->andReturn(m::mock(CommandParameters::class)
                ->shouldReceive('toArray')
                ->andReturn([])
                ->getMock()
            )->getMock();

        $constraints = [
            ['username', Constraint::class, true]
        ];

        $commandValidator = m::mock(CommandValidator::class, [$event, $constraints])
            ->shouldAllowMockingProtectedMethods()
            ->makePartial()
            ->shouldReceive('isValid')
            ->andReturnTrue()
            ->getMock();

        $consoleResponse = $commandValidator->validate();
        $this->assertThat($consoleResponse, self::isInstanceOf(ConsoleResponse::class));
    }

    /**
     * @throws CommandException
     * @doesNotPerformAssertions
     */
    public function testShouldValidateFail()
    {
        $this->expectException(CommandException::class);
        $this->expectExceptionMessage('Invalid parameter testparameter');

        $event = m::mock(Event::class)
            ->shouldReceive('getParameters')
            ->andReturn(m::mock(CommandParameters::class)
                ->shouldReceive('toArray')
                ->andReturn([])
                ->getMock()
            )->getMock();

        $commandValidator = m::mock(CommandValidator::class, [$event, []])
            ->shouldAllowMockingProtectedMethods()
            ->makePartial()
            ->shouldReceive('isValid')
            ->andReturnFalse()
            ->getMock()
            ->shouldReceive('getErrors')
            ->andReturn(['Invalid parameter testparameter'])
            ->getMock();

        $consoleResponse = $commandValidator->validate();
    }


    ///usunac

    public function testShouldValidateCommandParametersSuccess()
    {
        $event = m::mock(Event::class)
            ->shouldReceive('getParameters')
            ->andReturn(
                m::mock(CommandParameters::class)
                    ->shouldReceive('toArray')
                    ->andReturn([
                        'testparameter' => 'testbalue',
                    ])
                    ->getMock()
            )
            ->getMock();

        $constraints = [
            ['testparameter', ConstraintDecorator::class, false],
        ];

        $commandValidator = new CommandValidator($event, $constraints);
        $consoleResponse = $commandValidator->validate();

        $this->assertThat($consoleResponse, self::isInstanceOf(ConsoleResponse::class));
    }

    public function testShouldValidateOptionalCommandParametersSuccess()
    {

        $event = m::mock(Event::class)
            ->shouldReceive('getParameters')
            ->andReturn(
                m::mock(CommandParameters::class)
                    ->shouldReceive('toArray')
                    ->andReturn([])
                    ->getMock()
            )
            ->getMock();

        $constraints = [
            ['testparameter', ConstraintDecorator::class, true],
        ];

        $commandValidator = new CommandValidator($event, $constraints);
        $consoleResponse = $commandValidator->validate();

        $this->assertThat($consoleResponse, self::isInstanceOf(ConsoleResponse::class));
    }

    /**
     * @doesNotPerformAssertions
     * @throws \Application\Console\Command\CommandException
     */
    public function testShouldValidateCommandParametersFail()
    {
        $this->expectException(CommandException::class);
        $this->expectExceptionMessage('Invalid parameter testparameter');

        $event = m::mock(Event::class)
            ->shouldReceive('getParameters')
            ->andReturn(
                m::mock(CommandParameters::class)
                    ->shouldReceive('toArray')
                    ->andReturn([])
                    ->getMock()
            )
            ->getMock();

        $constraints = [
            ['testparameter', ConstraintDecoratorFailed::class, false],
        ];

        $commandValidator = new CommandValidator($event, $constraints);
        $consoleResponse = $commandValidator->validate();
    }

}