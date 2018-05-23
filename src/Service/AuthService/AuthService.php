<?php

namespace Application\Service\AuthService;

use Application\Config\Config;
use Application\Router\Router;
use Application\Service\Request\Request;
use Application\Service\ServiceInterface;
use Model\Base\Role;
use Model\UserAuthTokenQuery;
use Model\UserPrivilege;
use Model\UserQuery;
use Model\Privilege;
use Model\UserRole;

class AuthService implements ServiceInterface
{
    const AUTH_KEY_NAME = 'authToken';
    const LOGIN_CONTROLLER = 'Admin\UserController';
    const LOGIN_ACTION = 'loginAction';

    private $request;
    private $sessionToken;

    /** @var \Model\User $user */
    private $user;

    /**
     * AuthService constructor.
     * @param Request $request
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->isAuthenticated();
    }

    /**
     * @param $username
     * @param $password
     * @return mixed|\Model\User
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function authenticate($username, $password)
    {
        $password = md5($password);
        $token = $this->createAuthToken($username, $password);

        /** @var \Model\User user */
        $this->user = UserQuery::create()->findOneByArray([
            'username' => $username,
            'password' => $password
        ]);

        if(!$this->user)
        {
            return false;
        }

        $userAuthToken = new \Model\UserAuthToken();
        $userAuthToken->setToken($token);
        $userAuthToken->setUser($this->user);
        $userAuthToken->save();
        $this->request->getSession()->set(self::AUTH_KEY_NAME, $token);

        return $this->user;
    }

    /**
     * @return bool
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function isAuthenticated()
    {
        if(!$this->request->getSession()->get(self::AUTH_KEY_NAME))
        {
            return false;
        }

        $userAuthToken = UserAuthTokenQuery::create()
            ->where('UserAuthToken.token = ?', $this->request->getSession()->get(self::AUTH_KEY_NAME))
            ->orderByCreatedAt(UserAuthTokenQuery::DESC)
            ->findOne();

        if(($userAuthToken instanceof \Model\UserAuthToken))
        {
            $this->user = $userAuthToken->getUser();
            return ($this->user instanceof \Model\User);
        }

        return false;
    }

    /**
     * @return bool
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function hasAccess()
    {
        $route = $this->request->getRoute();

        if(!$this->user && ($route->getController() === self::LOGIN_CONTROLLER && $route->getAction() === self::LOGIN_ACTION))
        {
            return true;
        }

        if(!$this->user)
        {
            return false;
        }

        $userPrivileges = $this->user->getUserPrivileges();
        $userRoles = $this->user->getUserRoles();

        foreach($userPrivileges as  $userPrivilege)
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

    public function createAuthToken($username, $password)
    {
        // sprobować przerobić tak, żeby dodać salt zależnie od czasu trwania sesji.
        // np sesja jest ważna 1h to dodać do niej salt 'Y-m-d H'
        return md5($username . $password);
    }

    public function clearSession()
    {
        $this->request->getSession()->clear();
    }

    public function getSessionToken()
    {
        return $this->sessionToken;
    }

    public function getUser()
    {
        return $this->user;
    }
}

