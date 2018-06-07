<?php

namespace Application\Service\AccessChecker;

use Application\Router\Route;
use Application\Service\AuthService\AuthService;
use Application\Service\Request\Request;
use Application\Service\ServiceInterface;
use Model\User;
use Model\UserPrivilege;
use Model\UserRole;

class AccessChecker implements ServiceInterface
{
    private $request;
    /** @var AuthService $authService */
    private $authService;

    public function __construct(Request $request, AuthService $authService)
    {
        $this->request = $request;
        $this->authService = $authService;
    }

    /**
     * @param Route $route
     * @return bool
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function hasAccess(Route $route)
    {
        if($route->getAccess() === Route::ACCESS_PUBLIC)
        {
            return true;
        }

        $user = $this->authService->getUser();

        if(!($user instanceof User))
        {
            return false;
        }

        $userPrivileges = $user->getUserPrivileges();
        $userRoles = $user->getUserRoles();

        foreach($userPrivileges as $userPrivilege)
        {
            if($this->checkPrivilege($userPrivilege))
            {
                return true;
            }
        }

        /** @var UserRole $userRole */
        foreach($userRoles as $userRole)
        {
            foreach($userRole->getRole()->getPrivileges() as $privilege)
            {
                if($this->checkPrivilege($privilege))
                {
                    return true;
                }
            }
        }

        return false;
    }

    private function checkPrivilege(UserPrivilege $privilege)
    {
        $route = $this->request->getRoute();

        return $privilege->getName() === $route->getCompactName();
    }

}