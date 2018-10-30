<?php

namespace tests\Service\AccessChecker;

use Application\Service\Router\Route;
use Application\Service\AccessChecker\AccessChecker;
use Application\Service\AuthService\AuthService;
use Model\Role;
use Model\User;
use Model\UserPrivilege;
use Model\UserRole;
use Mockery as m;
use PHPUnit\Framework\TestCase;


class AccessCheckerTest extends TestCase
{
    public function testShouldCheckIfUserHasAccessByRolePriviledge()
    {
        $authService = m::mock(AuthService::class)
            ->shouldReceive('getUser')
            ->andReturn(
                m::mock(User::class)
                    ->shouldReceive('getUserPrivileges')
                    ->andReturns([])
                    ->getMock()
                    ->shouldReceive('getUserRoles')
                    ->andReturns([
                        m::mock(UserRole::class)
                            ->shouldReceive('getRole')
                            ->andReturn(
                                m::mock(Role::class)
                                    ->shouldReceive('getPrivileges')
                                    ->andReturns([
                                        m::mock(UserPrivilege::class)
                                            ->shouldReceive('getName')
                                            ->andReturn('/test/route')
                                            ->getMock()
                                    ])->getMock()
                            )
                            ->getMock()
                    ])
                    ->getMock()
            )->getMock();

        $routeMock = m::mock(Route::class)
            ->shouldReceive('getName')
            ->andReturn('/test/route')
            ->getMock();

        $accessChecker = new AccessChecker($authService);

        $this->assertTrue($accessChecker->hasAccess($routeMock));
    }

    public function testShouldCheckIfUserHasAccessByPriviledge()
    {
        $authService = m::mock(AuthService::class)
            ->shouldReceive('getUser')
            ->andReturn(
                m::mock(User::class)
                    ->shouldReceive('getUserPrivileges')
                    ->andReturns([
                        m::mock(UserPrivilege::class)
                            ->shouldReceive('getName')
                            ->andReturn('/test/route')
                            ->getMock()
                    ])
                    ->getMock()
                    ->shouldReceive('getUserRoles')
                    ->andReturnNull()
                    ->getMock()
            )->getMock();

        $routeMock = m::mock(Route::class)
            ->shouldReceive('getName')
            ->andReturn('/test/route')
            ->getMock();

        $accessChecker = new AccessChecker($authService);

        $this->assertTrue($accessChecker->hasAccess($routeMock));
    }
}