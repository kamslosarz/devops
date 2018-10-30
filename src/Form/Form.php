<?php

namespace Application\Form;

use Application\Form\FormBuilder\Field\Field;
use Application\Form\FormBuilder\FormBuilder;
use Application\Form\FormHandler\FormHandler;
use Application\Service\Request\Request;
use Application\Service\Router\Router;
use Application\Service\ServiceContainer\ServiceContainer;
use Application\Service\Translator\Translator;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;

abstract class Form implements FormInterface
{
    protected $formHandler;
    protected $entity;
    protected $data;
    protected $attributes;
    /** @var Translator $translator */
    protected $translator;
    /** @var Router $router */
    protected $router;

    /**
     * Form constructor.
     * @param null $entity
     * @param ServiceContainer $serviceContainer
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function __construct($entity = null, ServiceContainer $serviceContainer)
    {
        $this->entity = $entity;
        $this->formBuilder = new FormBuilder();
        $this->formHandler = new FormHandler();
        $this->translator = $serviceContainer->getService('translator');
        $this->router = $serviceContainer->getService('router');

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

    abstract protected function build(): FormBuilder;

    abstract public function getAttributes(): array;

    public function renderView(): FormView
    {
        return new FormView($this);
    }

    public function handle(Request $request): bool
    {
        return $this->formHandler->handle($this, $request);
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getData($key = null): array
    {
        return is_null($key) ? $this->data : $this->data[$key];
    }

    public function getEntity(): ActiveRecordInterface
    {
        return $this->entity;
    }

    public function hasEntity(): bool
    {
        return !is_null($this->entity);
    }

    public function getAttribute($attribute): array
    {
        return isset($this->getAttributes()[$attribute]) ? $this->getAttributes()[$attribute] : null;
    }

    public function getUrl($route, array $parameters = null): string
    {
        return $this->router->getUrl('/admin/login', $parameters);
    }

    public function translate($phrase, array $variables = null): Phrase
    {
        return $this->translator->translate($phrase, $variables);
    }
}