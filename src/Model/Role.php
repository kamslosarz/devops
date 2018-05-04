<?php

namespace Application\Model;

use Application\Model\Traits\LifecycleTrait;
use Application\Model\Traits\SoftDeleteTrait;

/**
 * @Entity
 * @Table(name="roles")
 * @HasLifecycleCallbacks
 * @SoftDeleteable(fieldName="deleted", timeAware=false)
 */
class Role
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
     * @var string
     */
    protected $name;

    /**
     * @OneToMany(targetEntity="Privilege", mappedBy="role")
     */
    protected $privileges;

    public function getId()
    {
        return $this->id;
    }

    public function getPrivileges()
    {
        return $this->privileges;
    }

    public function addPrivilege(Privilege $privilege)
    {
        $this->privileges[] = $privilege;

        return $this;
    }
}