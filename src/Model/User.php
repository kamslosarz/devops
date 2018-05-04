<?php

namespace Application\Model;

use Application\Model\Traits\LifecycleTrait;
use Application\Model\Traits\SoftDeleteTrait;
use Doctrine\Common\Collections\Collection;

/**
 * @Entity
 * @Table(name="users")
 * @HasLifecycleCallbacks
 * @SoftDeleteable(fieldName="deleted", timeAware=false)
 */
class User
{
    use SoftDeleteTrait;
    use LifecycleTrait;

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Column(type="string")
     */
    protected $username;

    /**
     * @Column(type="string")
     */
    protected $password;

     /**
     * @var Collection
     * @OneToMany(targetEntity="UserAuthToken", mappedBy="user")
     */
    protected $userAuthTokens = [];

    /**
     * @ManyToMany(targetEntity="Role")
     * @JoinTable(name="users_phonenumbers",
     *  joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *  inverseJoinColumns={@JoinColumn(name="role_id", referencedColumnName="id", unique=true)}
     * )
     */
    protected $roles = [];

    /**
     * @OneToMany(targetEntity="Privilege", mappedBy="user")
     */
    protected $privileges = [];


    public function __construct()
    {
        $this->userAuthTokens = new \Doctrine\Common\Collections\ArrayCollection();
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->privileges = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function addRole(Role $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function addPrivilege(Privilege $privilege)
    {
        $this->privileges[] = $privilege;
        $privilege->setUser($this);

        return $this;
    }

    public function getPrivileges()
    {
        return $this->privileges;
    }
}