<?php

class AuthServiceTests extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function testShouldReturnAuthService()
    {
        $serviceContainer = new \Application\Service\ServiceContainer\ServiceContainer();
        $authService = $serviceContainer->getService('auth');

        $this->assertInstanceOf(\Application\Service\AuthService\AuthService::class, $authService);
    }

}
