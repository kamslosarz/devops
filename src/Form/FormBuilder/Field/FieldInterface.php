<?php

namespace Application\Form\FormBuilder\Field;

interface FieldInterface
{
    public function getTagname(): string;

    public function isValid(): bool;
}