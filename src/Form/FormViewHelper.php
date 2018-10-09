<?php

namespace Application\Form;

use Application\Form\FormBuilder\Field\Field;
use Application\Form\FormBuilder\FormBuilder;

class FormViewHelper
{
    /** @var FormInterface $form */
    private $form;

    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    public function get($name)
    {
        return $this->form->getFormBuilder()->getField($name);
    }

    public function getFields()
    {
        return $this->form->getFormBuilder()->getFields();
    }

    public function getName($name = '')
    {
        if(!$name)
        {
            return $this->form->getName();
        }

        return sprintf('%s[%s]', $this->form->getName(), $name);
    }

    public function getAction()
    {
        return $this->form->getAction();
    }

    public function getMethod()
    {
        return $this->form->getMethod();
    }

    public function getTitle()
    {
        return $this->form->getTitle();
    }

    public function getLabel($fieldName)
    {
        return lcfirst(ucwords(trim(strtolower($fieldName))));
    }

    public function getClass()
    {
        return $this->form->getClass();
    }
}