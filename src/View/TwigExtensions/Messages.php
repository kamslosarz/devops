<?php

namespace Application\View\TwigExtensions;

class Messages extends Extension implements \Twig_Extension_GlobalsInterface
{
    public function getGlobals()
    {
        return [
            'messages' => $this
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_Function('messages', [$this, 'messages'])
        ];
    }

    public function all()
    {
        //return $this->serviceContainer->getAppender()->flashMessages();
    }
}