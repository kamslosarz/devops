<?php

namespace Test\TestCase;

use Application\Form\FormBuilder\Field\FieldInterface;
use Application\Form\FormView;
use Test\Decorator\FormViewDecorator;
use Test\Fixture\TestForm;

abstract class FormViewTestCase extends ViewTestCase
{
    /**
     * @param $form
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderForm(FormView $form)
    {
        return $this->getTwig()->render('form/form.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @param FieldInterface $field
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderField(FieldInterface $field)
    {
        return $this->getTwig()->render(sprintf('form/fields/%s.html.twig', $field->getTagname()), [
            'field' => $field,
            'form' => $this->getFormDecorator()
        ]);
    }

    private function getFormDecorator(){

        return new FormViewDecorator(new TestForm());
    }

}