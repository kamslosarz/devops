<?php

namespace Application\Model;

/**
 * @Entity @Table(name="project_builds")
 */
class Build
{
    /**
     * @Id @GeneratedValue @Column(type="integer")
     * @var string
     */
    protected $id;

}