<?php

namespace Application\Model\Traits;

/**
 * @SoftDeleteable(fieldName="deleted", timeAware=false)
 */
trait SoftDeleteTrait
{
    /**
     * @Column(type="datetime", nullable=true)
     */
    protected $deleted;

    public function getDeleted()
    {
        return $this->deleted;
    }

    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }
}