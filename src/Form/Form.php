<?php

namespace Application\Form;

use Application\Form\FormBuilder\FormBuilder;

abstract class Form
{
    CONST METHOD_POST = 'post';
    CONST METHOD_GET = 'get';

    private $formBuilder;
    protected $entity;

    public function __construct($entity = null)
    {
        $this->entity = $entity;
        $this->formBuilder = new FormBuilder();

        $this->build($this->formBuilder);
    }

    public function view()
    {
        return new FormView($this);
    }

    protected function build(FormBuilder $formBuilder)
    {

    }

    public function getFormBuilder()
    {
        return $this->formBuilder;
    }
}