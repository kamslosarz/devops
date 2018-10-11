<?php

namespace Application\Form;

use Application\Form\FormBuilder\Field\Field;
use Application\Form\FormBuilder\FormBuilder;

class FormView
{
    /** @var FormInterface $form */
    private $form;

    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    public function get($fieldName)
    {
        return $this->form->getFormBuilder()->getField($fieldName);
    }

    public function getFields()
    {
        return $this->form->getFormBuilder()->getFields();
    }

    public function getName($name = '')
    {
        return $name ? sprintf('%s[%s]', $this->form->getAttribute('name'), $name) : $this->form->getAttribute('name');
    }

    public function getLabel($fieldName)
    {
        return lcfirst(ucwords(trim(strtolower($fieldName))));
    }

    public function getMethod()
    {
        return $this->form->getMethod();
    }

    public function getAction()
    {
        return $this->form->getAttribute('action');
    }

    public function getTitle()
    {
        return $this->form->getAttribute('title');
    }

    public function getClass()
    {
        return $this->form->getAttribute('class');
    }
}