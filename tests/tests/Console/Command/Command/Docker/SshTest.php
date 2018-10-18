<?php

namespace tests\Console\Command\Command\Docker;

use Application\Console\Command\Command\CommandParameters;
use Application\Console\Command\Command\Docker\Ssh;
use PHPUnit\Framework\TestCase;
use Mockery as m;

class SshTest extends TestCase
{
    /**
     * @doesNotPerformAssertions
     * @dataProvider differentUserParamDataProvider
     * @param $user
     * @param $parameterExists
     */
    public function testShouldExecuteInShellDockerCommand($user, $parameterExists)
    {
        /** @var m\MockInterface $ssh */
        $ssh = m::mock(Ssh::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial()
            ->shouldReceive('executeInShell')
            ->once()
            ->withArgs(['docker exec -it -u %s %s /bin/bash', [$user, Ssh::DOCKER_CONTAINER_NAME]])
            ->getMock();

        $ssh->execute(m::mock(CommandParameters::class)
            ->shouldReceive('offsetExists')
            ->with(0)
            ->andReturns($parameterExists)
            ->getMock()
            ->shouldReceive('offsetGet')
            ->with(0)
            ->andReturn($user)
            ->getMock());

        $ssh->shouldHaveReceived('executeInShell')->once();
    }

    public function differentUserParamDataProvider()
    {
        return [
            'Test case 1' => [
                'devops',
                true
            ],
            'Test case 2' => [
                'root',
                true
            ]
        ];
    }

}
