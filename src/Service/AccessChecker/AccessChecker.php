<?php

namespace Application\Service\AccessChecker;

use Application\Config\Config;
use Application\Router\Route;
use Application\Router\Router;
use Application\Service\AuthService\AuthService;
use Application\Service\Request\Request;
use Application\Service\ServiceInterface;
use Model\User;
use Model\UserPrivilege;
use Model\UserRole;

class AccessChecker implements ServiceInterface
{
    private $request;
    private $authService;

    public function __construct(Request $request, AuthService $authService)
    {
        $this->request = $request;
        $this->authService = $authService;
    }

    /**
     * @return bool
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function hasAccess()
    {
        $route = $this->request->getRoute();

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

        return Router::getCompactRouteName($route->getController(), $route->getAction()) === Config::get('defaultAction');
    }

    private function checkPrivilege(UserPrivilege $privilege)
    {
        $route = $this->request->getRoute();

        return $privilege->getName() === Router::getCompactRouteName($route->getController(), $route->getAction());
    }

}