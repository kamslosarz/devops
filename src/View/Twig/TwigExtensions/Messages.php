<?php

namespace Application\View\Twig\TwigExtensions;

class Messages extends Extension implements \Twig_Extension_GlobalsInterface
{
    public function getGlobals(): array
    {
        return [
            'messages' => $this
        ];
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_Function('messages', [$this, 'messages'])
        ];
    }

    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function getAll()
    {
        $messages = $this->serviceContainer->getService('session')->get('messages');
        $this->serviceContainer->getService('session')->set('messages', null);

        return $messages;
    }
}