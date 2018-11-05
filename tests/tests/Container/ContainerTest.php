<?php

use Application\Container\Container;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    use \Test\TestCase\Traits\ServiceContainerMockBuilderTrait;

    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function testShouldConstructContainer()
    {
        $container = new Container(include FIXTURE_DIR . '/config/servicesMap.php');

        $this->assertThat($container, self::isInstanceOf(Container::class));
    }

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
        $text = $crawler->text();

        $this->assertEquals('ACTION', trim($text));
    }

    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function testShouldThrowEventManagerExceptionWhenRequestUriIsNotMatchWithAnyRoute()
    {
        $this->expectException(\Application\EventManager\EventManagerException::class);
        $this->expectExceptionMessage('Route not exists');

        $_SERVER['REQUEST_URI'] = '/test/route';
        $serviceContainerConfig = $this->getServiceContainerConfig();
        unset($serviceContainerConfig['router'][1]['routes']['/test/route']);
        $container = new Container($serviceContainerConfig);
        $response = $container()->getResponse();

        throw $response->getParameters()['exception'];
    }

    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function testShouldReturnErrorResponseOnEventManagerDispatchingException()
    {
        $serviceContainerConfig = $this->getServiceContainerConfig();
        unset($serviceContainerConfig['router']);

        $_SERVER['REQUEST_URI'] = '/test/route';
        $container = new Container($serviceContainerConfig);
        $container();
        $response = $container->getResponse();

        $this->assertThat($response, self::isInstanceOf(\Application\Response\ResponseTypes\ErrorResponse::class));
    }

    /**
     * @param $type
     * @param $postData
     * @param $expectedResponse
     * @param $expectedContent
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @dataProvider differentResponseTypes
     */
    public function testShouldInvokeViewAndSetResponseContent($type, $postData, $expectedResponse, $expectedContent)
    {
        $_SERVER['REQUEST_URI'] = sprintf('/test/differentResponses/%s', $type);
        $_POST['responseData'] = $postData;

        $container = new Container($this->getServiceContainerConfig());
        $container();
        $response = $container->getResponse();

        $this->assertThat($response, self::isInstanceOf($expectedResponse));

        if($type === \Application\Response\ResponseTypes::REDIRECT)
        {
            $this->assertThat($response->getHeaders(), self::equalTo($expectedContent));
            $this->assertThat($response->getContent(), self::isEmpty());
        }
        else
        {
            $this->assertThat($response->getContent(), self::equalTo($expectedContent));
        }
    }

    public function differentResponseTypes()
    {
        return [
            'test case json' => [
                \Application\Response\ResponseTypes::JSON,
                ['json-data-fixture' => 'some-data'],
                \Application\Response\ResponseTypes\JsonResponse::class,
                json_encode(['json-data-fixture' => 'some-data'])
            ],
            'test case html' => [
                \Application\Response\ResponseTypes::HTML,
                [
                    'resource' => 'test-with-parameters.html.twig',
                    'parameters' => [
                        'list' => [
                            'param1' => 'value1',
                            'param2' => 'value2',
                        ]
                    ]
                ],
                \Application\Response\Response::class,
                '<div><h1>Parameters: </h1><ul><li>param1=> value1</li><li>param2=> value2</li></ul></div>'
            ],
            'test case redirect' => [
                \Application\Response\ResponseTypes::REDIRECT,
                '/redirect/to/some/page',
                \Application\Response\ResponseTypes\RedirectResponse::class,
                ['Location: /redirect/to/some/page']
            ],
            'test case error' => [
                \Application\Response\ResponseTypes::ERROR,
                ['exception' => new \Exception('Some error message')],
                \Application\Response\ResponseTypes\ErrorResponse::class,
                'ERROR Some error message'
            ]
        ];
    }

}