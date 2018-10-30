<?php

use Application\Container\Container;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    use \Test\TestCase\Traits\ServiceContainerMockBuilderTrait;

    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Application\View\Twig\TwigFactoryException
     */
    public function testShouldInvokeContainer()
    {
        $_SERVER['REQUEST_URI'] = '/test/route';
        $container = new Container($this->getServiceContainerConfig());
        $response = $container()->getResponse();
        $this->assertThat($response->getParameters(), self::equalTo(['testRouteAction']));

        $crawler = new \Symfony\Component\DomCrawler\Crawler($response->getContent());
        $text = $crawler->filterXPath('//body/div/div[@class="main-content"]')->text();
        $this->assertEquals('test', trim($text));
    }
}