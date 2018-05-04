<?php

namespace Application\Model;

use Application\Model\Traits\LifecycleTrait;
use Application\Model\Traits\SoftDeleteTrait;

/**
 * @Entity
 * @Table(name="privilege")
 * @HasLifecycleCallbacks
 * @SoftDeleteable(fieldName="deleted", timeAware=false)
 */
class Privilege
{
    use SoftDeleteTrait;
    use LifecycleTrait;

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     * @var string
     */
    protected $id;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $name;

    /**
     * @manyToOne(targetEntity="User", inversedBy="privileges")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @manyToOne(targetEntity="Role", inversedBy="privileges")
     * @JoinColumn(name="role_id", referencedColumnName="id")
     */
    protected $role;

    public function setRole(Role $role)
    {
        $this->role = $role;

        return $this;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}