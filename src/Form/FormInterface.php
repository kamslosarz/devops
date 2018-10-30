<?php

namespace Application\Form;


interface FormInterface
{
    public function getAttributes(): array ;

    public function getMethod(): string ;
}