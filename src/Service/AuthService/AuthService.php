<?php

namespace Application\Service\AuthService;

use Application\Model\Privilege;
use Application\Model\Role;
use Application\Model\User;
use Application\Model\UserAuthToken;
use Application\Router\Router;
use Application\Service\Orm\Orm;
use Application\Service\Request\Request;
use Application\Service\ServiceInterface;

class AuthService implements ServiceInterface
{
    const AUTH_KEY_NAME = 'authToken';
    const LOGIN_CONTROLLER = 'Admin\UserController';
    const LOGIN_ACTION = 'loginAction';

    private $request;
    private $sessionToken;
    private $entityManager;
    /** @var User $user */
    private $user;

    /**
     * AuthService constructor.
     * @param Request $request
     * @param Orm $entityManager
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function __construct(Request $request, Orm $entityManager)
    {
        $this->request = $request;
        $this->entityManager = $entityManager->getEntityManager();
        $this->isAuthenticated();
    }

    public function authenticate($username, $password)
    {
        $password = md5($password);
        $token = $this->createAuthToken($username, $password);

        $this->user = $this->entityManager->getRepository(User::class)->findOneBy([
            'username' => $username,
            'password' => $password
        ]);

        $userAuthToken = new UserAuthToken();
        $userAuthToken->setUser($this->user);
        $userAuthToken->setToken($token);
        $this->request->getSession()->set(self::AUTH_KEY_NAME, $token);
        $this->entityManager->persist($userAuthToken);
        $this->entityManager->flush();

        return $this->user;
    }

    /**
     * @return bool
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function isAuthenticated()
    {
        if(!$this->request->getSession()->get(self::AUTH_KEY_NAME))
        {
            return false;
        }

        $userAuthToken = $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(UserAuthToken::class, 'u')
            ->where('u.token = :token')
            ->andWhere('u.created >= :created')
            ->orderBy('u.created', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->setParameters([
                'token' => $this->request->getSession()->get(self::AUTH_KEY_NAME),
                'created' => date("Y-m-d H:i:s", strtotime('-15 minutes'))
            ])
            ->getQuery()
            ->getSingleResult();

        if(!($userAuthToken instanceof UserAuthToken))
        {
            return false;
        }
        $this->user = $userAuthToken->getUser();

        return ($this->user instanceof User) ? true : false;
    }

    /**
     * @return bool
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

        $userPrivileges = $this->user->getPrivileges();
        $userRoles = $this->user->getRoles();

        foreach($userPrivileges as $privilege)
        {
            if($this->checkPrivilege($privilege))
            {
                return true;
            }
        }

        /** @var Role $role */
        foreach($userRoles as $role)
        {
            foreach($role->getPrivileges() as $privilege)
            {
                if($this->checkPrivilege($privilege))
                {
                    return true;
                }
            }
        }

        return false;
    }

    private function checkPrivilege(Privilege $privilege)
    {
        $route = $this->request->getRoute();

        return $privilege->getName() === Router::getCompactName($route->getController(), $route->getAction());
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

