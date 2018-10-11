<?php

namespace Application\Form;

use Application\Form\FormBuilder\Field\Field;
use Application\Form\FormBuilder\FormBuilder;
use Application\Form\FormHandler\FormHandler;
use Application\Router\Router;
use Application\Service\Request\Request;
use Application\Service\Translator\Translator;

abstract class Form implements FormInterface
{
    protected $formHandler;
    protected $entity;
    protected $data;
    protected $attributes;
    protected $translator;
    protected $router;

    public function __construct($entity = null, Translator $translator, Router $router)
    {
        $this->entity = $entity;
        $this->formBuilder = new FormBuilder();
        $this->formHandler = new FormHandler();
        $this->translator = $translator;
        $this->router = $router;

        $this->build();

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

    abstract protected function build();

    public function renderView()
    {
        return new FormView($this);
    }

    public function handle(Request $request)
    {
        return $this->formHandler->handle($this, $request);
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function getData($key = null)
    {
        return is_null($key) ? $this->data : $this->data[$key];
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function hasEntity()
    {
        return !is_null($this->entity);
    }

    abstract public function getAttributes();

    public function getAttribute($attribute)
    {
        return isset($this->getAttributes()[$attribute]) ? $this->getAttributes()[$attribute] : null;
    }
}