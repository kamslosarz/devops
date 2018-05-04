<?php

namespace Application\Model\Traits;

/**
 * @HasLifecycleCallbacks
 */
trait LifecycleTrait
{
    /**
     * @var datetime $created
     *
     * @Column(type="datetime", nullable=true)
     */
    protected $created;

    /**
     * @var datetime $updated
     *
     * @Column(type="datetime", nullable=true)
     */
    protected $updated;


    /**
     * @PrePersist
     */
    public function onPrePersist()
    {
        $this->created = new \DateTime("now");
    }

    /**
     * @PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updated = new \DateTime("now");
    }
}