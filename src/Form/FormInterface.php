<?php

namespace Application\Form;


interface FormInterface
{
    public function getAction();

    public function getName();

    public function getTitle();

    public function getMethod();
}