<?php

namespace Application\Model;

use Application\Model\Traits\LifecycleTrait;
use Application\Model\Traits\SoftDeleteTrait;


/**
 * @Entity @Table(name="user_auth_token")
 */
class UserAuthToken
{
    use SoftDeleteTrait;
    use LifecycleTrait;

    /**
     * @Id @GeneratedValue @Column(type="integer")
     * @var string
     */
    protected $id;

    /**
     * @manyToOne(targetEntity="User")
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


}