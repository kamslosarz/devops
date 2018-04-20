<?php

class AuthServiceTests extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \Application\ServiceContainer\ServiceContainerException
     */
    public function testShouldReturnAuthService()
    {
        $serviceContainer = new \Application\ServiceContainer\ServiceContainer();
        $authService = $serviceContainer->getService('authService');

        $this->assertInstanceOf(\Application\Service\AuthService\AuthService::class, $authService);
    }

}
