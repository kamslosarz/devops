<?php

namespace Application\Form\FormHandler;


use Application\Form\Form;
use Application\Service\Request\Request;
use Application\Service\Request\RequestMethods;

class FormHandler
{
    public function handle(Form $form, Request $request)
    {
        if($form->getMethod() === RequestMethods::POST)
        {
            $form->setData($request->post($form->getAttribute('name')));
        }
        elseif($form->getMethod() === RequestMethods::GET)
        {
            $form->setData($request->get($form->getAttribute('name')));
        }
        else
        {
            return false;
        }

        if($form->hasEntity())
        {
            $entity = $form->getEntity();

            foreach($form->getData() as $property => $value)
            {
                $setter = 'set' . ucfirst($property);
                $entity->$setter($value);
            }

            return $entity->save();
        }

        return true;
    }
}