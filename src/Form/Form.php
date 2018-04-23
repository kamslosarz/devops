<?php

namespace Application\Form;

use Application\Form\FormBuilder\Field\Field;
use Application\Form\FormBuilder\FormBuilder;
use Application\Form\FormHandler\FormHandler;
use Application\Service\Request\Request;

abstract class Form
{
    private $formBuilder;
    private $formHandler;
    protected $entity;
    protected $data;

    public function __construct($entity = null)
    {
        $this->entity = $entity;
        $this->formBuilder = new FormBuilder();
        $this->formHandler = new FormHandler();

        $this->build($this->formBuilder);

        if($this->hasEntity())
        {
            /** @var Field $field */
            foreach($this->formBuilder->getFields() as $name => &$field)
            {
                $getter = 'get' . ucfirst($name);
                $field->setValue($entity->$getter());
            }
        }
    }

    public function view()
    {
        return new FormViewHelper($this);
    }

    protected function build(FormBuilder $formBuilder)
    {

    }

    public function getFormBuilder()
    {
        return $this->formBuilder;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function handle(Request $request)
    {
        return $this->formHandler->handle($this, $request);
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getName() { }

    public function getMethod() { }

    public function getEntity()
    {
        return $this->entity;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    public function hasEntity()
    {
        return !is_null($this->entity);
    }

}