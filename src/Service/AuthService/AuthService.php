<?php

namespace Application\Service\AuthService;

use Application\Service\Request\Request;
use Application\Service\ServiceInterface;
use Model\Privilege;
use Model\User;
use Model\UserAuthTokenQuery;
use Model\UserQuery;

class AuthService implements ServiceInterface
{
    const AUTH_KEY_NAME = 'authToken';

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
    public function authenticate($username, $password): User
    {
        $password = md5($password);

        /** @var \Model\User user */
        $this->user = UserQuery::create()->findOneByArray([
            'username' => $username,
            'password' => $password
        ]);

        if(!$this->user)
        {
            return false;
        }

        $token = $this->createAuthToken($username, $password);

        $userAuthToken = new \Model\UserAuthToken();
        $userAuthToken->setToken($token);
        $userAuthToken->setUser($this->user);
        $userAuthToken->save();
        $this->user->addUserAuthToken($userAuthToken);
        $this->user->save();
        $this->request->getSession()->set(self::AUTH_KEY_NAME, $token);

        return $this->user;
    }

    /**
     * @return bool
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function isAuthenticated(): bool
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

    public function createAuthToken($username, $password): string
    {
        return md5($username . $password);
    }

    public function clearSession(): void
    {
        $this->request->getSession()->clear(self::AUTH_KEY_NAME);
    }

    public function getSessionToken(): string
    {
        return $this->sessionToken;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}

