<?php

namespace Application\Model\Traits;

/**
 * Trait LifecycleTrait
 * @package Application\Model\Traits
 *
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
     * Gets triggered only on insert

     * @PrePersist
     */
    public function onPrePersist()
    {
        $this->created = new \DateTime("now");
    }

    /**
     * Gets triggered every time on update

     * @PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updated = new \DateTime("now");
    }
}