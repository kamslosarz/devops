<?php

namespace Application\Form;


interface FormInterface
{
    public function getAttributes();

    public function getMethod();
}