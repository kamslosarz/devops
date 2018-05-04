<?php

namespace Application\Model;

use Application\Model\Traits\LifecycleTrait;


/**
 * @Entity
 * @Table(name="user_auth_token")
 * @HasLifecycleCallbacks
 */
class UserAuthToken
{
    use LifecycleTrait;

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="userAuthTokens")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $token;

    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }


}