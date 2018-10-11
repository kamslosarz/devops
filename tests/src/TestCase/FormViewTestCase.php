<?php

namespace Test\TestCase;

use Application\Form\FormBuilder\Field\FieldInterface;
use Application\Form\FormView;
use Application\Router\Route;
use Application\Router\Router;
use Application\Service\Translator\Translator;
use function foo\func;
use Test\Fixture\TestForm;
use Mockery as m;


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
            'form' => $this->getForm()->renderView()
        ]);
    }

    /**
     * @param null $entity
     * @return TestForm
     */
    private function getForm($entity = null)
    {
        return new TestForm(
            $entity,
            m::mock(Translator::class)
                ->shouldReceive('translate')
                ->andReturnUsing(function ($routeName)
                {

                    return $routeName;
                })->getMock(),
            m::mock(Router::class)
                ->shouldReceive('getRouteByName')
                ->andReturn(
                    m::mock(Route::class)
                    ->shouldReceive('getUrl')
                    ->andReturn('/test/test/test')
                    ->getMock()
                )->getMock()
        );
    }
}