<?php

namespace Application\Service\AuthService;

use Application\Model\User;
use Application\Model\UserAuthToken;
use Application\Service\Orm\Orm;
use Application\Service\Request\Request;
use Application\Service\ServiceInterface;

class AuthService implements ServiceInterface
{
    const AUTH_COOKIE_NAME = 'authToken';

    private $request;
    private $sessionToken;
    private $entityManager;
    private $user;

    public function __construct(Request $request, Orm $entityManager)
    {
        $this->request = $request;
        $this->entityManager = $entityManager->getEntityManager();
    }

    public function authenticate($username, $password)
    {
        $password = md5($password);
        $token = $this->createAuthToken($username, $password);

        if(!$this->isAuthenticated($token))
        {
            $this->user = $this->entityManager->getRepository(User::class)->findOneBy([
                'username' => $username,
                'password' => $password
            ]);

            $userAuthToken = new UserAuthToken();
            $userAuthToken->setUser($this->user);
            $userAuthToken->setToken($token);
            $this->entityManager->persist($userAuthToken);
            $this->entityManager->flush();
        }

        return $this->user;
    }

    public function isAuthenticated($token)
    {
        $this->user = $this->entityManager->createQueryBuilder()
            ->select('u.token, u.user_id')
            ->from('user_auth_tokens', 'u')
            ->where('u.token==:token')
            ->andWhere('u.created_at >= :token_life')
            ->setParameters([
                'token' => $token,
                'token_life' => date("Y-m-d H:i:s", strtotime('-15 minutes'))
            ])
            ->getFirstResult();

        return ($this->user instanceof User) ? $this->user : false;
    }

    public function createAuthToken($username, $password)
    {
        return md5($username . $password);
    }

    public function clearSession()
    {
        $this->request->getSession()->set('token', null);
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