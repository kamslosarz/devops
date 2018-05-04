<?php

namespace Application\Model;

use Application\Model\Traits\LifecycleTrait;
use Application\Model\Traits\SoftDeleteTrait;

/**
 * @Entity
 * @Table(name="projects")
 * @HasLifecycleCallbacks
 * @SoftDeleteable(fieldName="deleted", timeAware=false)
 */
class Project
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
     * @Column(type="string")
     * @var string
     */
    protected $repository;

    public function __construct()
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setRepository($repository)
    {
        $this->repository = $repository;

        return $this;
    }

    public function getRepository()
    {
        return $this->repository;
    }
}