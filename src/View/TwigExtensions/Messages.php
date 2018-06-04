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

    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function all()
    {
        $messages = $this->serviceContainer->getService('session')->get('messages');
        $this->serviceContainer->getService('session')->set('messages', null);

        return $messages;
    }
}