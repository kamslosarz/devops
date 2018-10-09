<?php

namespace tests\Service\AccessChecker;

use Application\Router\Route;
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
                                            ->andReturn('app_route_name')
                                            ->getMock()
                                    ])->getMock()
                            )
                            ->getMock()
                    ])
                    ->getMock()
            )->getMock();

        $routeMock = m::mock(Route::class)
            ->shouldReceive('getAccess')
            ->andReturn(Route::ACCESS_PRIVATE)
            ->getMock()
            ->shouldReceive('getName')
            ->andReturn('app_route_name')
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
                            ->andReturn('app_route_name')
                            ->getMock()
                    ])
                    ->getMock()
                    ->shouldReceive('getUserRoles')
                    ->andReturnNull()
                    ->getMock()
            )->getMock();

        $routeMock = m::mock(Route::class)
            ->shouldReceive('getAccess')
            ->andReturn(Route::ACCESS_PRIVATE)
            ->getMock()
            ->shouldReceive('getName')
            ->andReturn('app_route_name')
            ->getMock();

        $accessChecker = new AccessChecker($authService);

        $this->assertTrue($accessChecker->hasAccess($routeMock));
    }

    public function testShouldCheckIfUserHasAccessToPublicRoute()
    {
        $authService = m::mock(AuthService::class)
            ->shouldReceive('getUser')
            ->andReturn(
                m::mock(User::class)
            )
            ->getMock();

        $routeMock = m::mock(Route::class)
            ->shouldReceive('getAccess')
            ->andReturn(Route::ACCESS_PUBLIC)
            ->getMock();

        $accessChecker = new AccessChecker($authService);

        $this->assertTrue($accessChecker->hasAccess($routeMock));
    }
}