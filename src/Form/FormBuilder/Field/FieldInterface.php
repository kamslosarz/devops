<?php

namespace Application\Form\FormBuilder\Field;

interface FieldInterface
{
    public function getTagname();

    public function isValid();
}