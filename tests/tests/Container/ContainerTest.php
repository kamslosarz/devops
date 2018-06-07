<?php

use Application\Container\Container;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    use \Test\TestCase\Traits\ServiceContainerMockBuilderTrait;

    public function testShouldConstructContainer()
    {
        $logger = m::mock(\Application\Service\Logger\Logger::class);

        $logger->shouldHaveReceived('log')
            ->atLeast(1)
            ->andReturns(true);

        $container = new Container();

        $this->assertInstanceOf(Container::class, $container);
    }

    public function testShouldInvokeAndReturnsResponse()
    {
        $container = new Container();
        $container();

        $this->assertEquals(1, preg_match_all('/<html lang=\"[a-z]+\">(.*?)<\/html>/si', $container->getResults()->getContent()));
    }

    /**
     * @throws \Application\Router\RouteException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Application\View\Twig\TwigFactoryException
     * @throws \Response\ResponseTypes\RedirectResponseException
     */
    public function testShouldAppendSessionWithMessages()
    {
        $sessionParameters = [
            'ERROR' => 'Test message for test usage'
        ];

        $serviceContainerMockBuilder = $this->getServiceContainerMockBuilder();
        $contextMock = m::mock(\Application\Context\Context::class)
            ->shouldHaveReceived('__invoke')
            ->getMock()
            ->shouldHaveReceived('getResults')
            ->andReturn(
                m::mock(\Application\Service\Request\Request::class)
                    ->shouldHaveReceived('getType')
                    ->getMock()
                    ->shouldHaveReceived('getRoute')
                    ->getMock()
                    ->shouldHaveReceived('getParameters')
                    ->getMock()
                    ->shouldHaveReceived('setContent')
                    ->getMock()
            )
            ->getMock();

        $appender = new \Application\Service\Appender\Appender(new \Application\Service\Session\Session());
        $appender->append('Test message for test usage', \Application\Service\Appender\AppenderLevel::ERROR);
        $serviceContainerMockBuilder->setAppenderMock($appender);

        $viewMock = m::mock(\Application\View\View::class)
            ->shouldHaveReceived('render')
            ->getMock();

        $container = new \Test\Decorator\ContainerDecorator(
            $serviceContainerMockBuilder->build(),
            $contextMock,
            $viewMock
        );

        $container();

        $this->assertEquals($sessionParameters, $_SESSION['messages']);
    }

    /**
     * @throws \Application\Router\RouteException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Application\View\Twig\TwigFactoryException
     * @throws \Response\ResponseTypes\RedirectResponseException
     */
    public function testShouldReturnRedirectResponseToDefaultAction()
    {
        $serviceContainerMockBuilder = $this->getServiceContainerMockBuilder();
        $serviceContainerMockBuilder->setAccessCheckerMock(
            m::mock(\Application\Service\AccessChecker\AccessChecker::class)
                ->shouldHaveReceived('hasAccess')
                ->once()
                ->withArgs(['Admin\AdminController:index'])
                ->andReturn(true)
                ->getMock()
        );
        $serviceContainerMockBuilder->setAppenderMock(
            m::mock(\Application\Service\Appender\Appender::class)
                ->shouldHaveReceived('append')
                ->once()
                ->withArgs(['Access denied to \'AdminController:indexAction\'', \Application\Service\Appender\AppenderLevel::ERROR])
                ->getMock()
        );

        $contextMock = m::mock(\Application\Context\Context::class)
            ->shouldHaveReceived('__invoke')
            ->andThrow(\Application\Service\AccessChecker\AccessDeniedException::class, 'Access denied to \'AdminController:indexAction\'')
            ->getMock();

        $viewMock = m::mock(\Application\View\View::class);

        $container = new \Test\Decorator\ContainerDecorator(
            $serviceContainerMockBuilder->build(),
            $contextMock,
            $viewMock
        );

        $container();

        /** @var \Application\Response\ResponseTypes\RedirectResponse $response */
        $response = $container->getResults();

        $this->assertInstanceOf(\Application\Response\ResponseTypes\RedirectResponse::class, $response);
        $this->assertEquals(['Location: /admin/index'], $response->getHeaders());
    }

    /**
     * @throws \Application\Router\RouteException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Application\View\Twig\TwigFactoryException
     * @throws \Response\ResponseTypes\RedirectResponseException
     */
    public function testShouldReturnRedirectResponseToLoginAction()
    {
        $serviceContainerMockBuilder = $this->getServiceContainerMockBuilder();
        $serviceContainerMockBuilder->setAccessCheckerMock(
            m::mock(\Application\Service\AccessChecker\AccessChecker::class)
                ->shouldHaveReceived('hasAccess')
                ->twice()
                ->andReturn(true)
                ->getMock()
        );
        $contextMock = m::mock(\Application\Context\Context::class)
            ->shouldHaveReceived('__invoke')
            ->andThrow(\Application\Service\AccessChecker\AccessDeniedException::class, 'Access denied to \'AdminController:loginAction\'')
            ->getMock();

        $viewMock = m::mock(\Application\View\View::class);

        $container = new \Test\Decorator\ContainerDecorator(
            $serviceContainerMockBuilder->build(),
            $contextMock,
            $viewMock
        );

        $container();

        /** @var \Application\Response\ResponseTypes\RedirectResponse $response */
        $response = $container->getResults();

        $this->assertInstanceOf(\Application\Response\ResponseTypes\RedirectResponse::class, $response);
        $this->assertEquals(['Location: /admin/index'], $response->getHeaders());
    }

    /**
     * @throws \Application\Router\RouteException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Application\View\Twig\TwigFactoryException
     * @throws \Response\ResponseTypes\RedirectResponseException
     */
    public function testShouldReturnErrorResponse()
    {
        $serviceContainerMockBuilder = $this->getServiceContainerMockBuilder();
        $serviceContainerMockBuilder->setAccessCheckerMock(
            m::mock(\Application\Service\AccessChecker\AccessChecker::class)
                ->shouldHaveReceived('hasAccess')
                ->twice()
                ->andReturn(true)
                ->getMock()
        );
        $contextMock = m::mock(\Application\Context\Context::class)
            ->shouldHaveReceived('__invoke')
            ->andThrow(\Application\Router\RouteException::class, 'Route \'/a/s/d/1\' not found')
            ->getMock();

        $viewMock = m::mock(\Application\View\View::class)
            ->shouldHaveReceived('render')
            ->withArgs(function (\Application\View\ViewElement $viewElement)
            {
                return (($viewElement->getParameters()['exception'] instanceof \Application\Router\RouteException) && $viewElement->getParameters()['exception']->getMessage() === 'Route \'/a/s/d/1\' not found');
            })
            ->andReturn('error: Route \'/a/s/d/1\' not found')
            ->getMock();

        $container = new \Test\Decorator\ContainerDecorator(
            $serviceContainerMockBuilder->build(),
            $contextMock,
            $viewMock
        );

        $container();

        /** @var \Application\Response\ResponseTypes\RedirectResponse $response */
        $response = $container->getResults();

        $this->assertInstanceOf(\Application\Response\ResponseTypes\ErrorResponse::class, $response);
        $this->assertInstanceOf(\Application\Router\RouteException::class, $response->getParameters()['exception']);
        $this->assertEquals('Route \'/a/s/d/1\' not found', $response->getParameters()['exception']->getMessage());
    }

    /**
     * @throws \Application\Router\RouteException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Application\View\Twig\TwigFactoryException
     * @throws \Response\ResponseTypes\RedirectResponseException
     */
    public function testShouldReturnJsonResponse()
    {
        $serviceContainerMockBuilder = $this->getServiceContainerMockBuilder();
        $serviceContainerMockBuilder->setRequestMock(
            m::mock(\Application\Service\Request\Request::class)
                ->shouldHaveReceived('getRequestUri')
                ->once()
                ->andReturn('/admin/json')
                ->getMock()
        );
        $responseParameters = [['test' => 'test']];
        $jsonResponse = new \Application\Response\ResponseTypes\JsonResponse($responseParameters);

        $contextMock = m::mock(\Application\Context\Context::class)
            ->shouldHaveReceived('__invoke')
            ->once()
            ->getMock()
            ->shouldHaveReceived('getResults')
            ->once()
            ->andReturn($jsonResponse)
            ->getMock();

        $viewMock = m::mock(\Application\View\View::class)
            ->shouldHaveReceived('render')
            ->getMock();

        $container = new \Test\Decorator\ContainerDecorator(
            $serviceContainerMockBuilder->build(),
            $contextMock,
            $viewMock
        );

        $container();

        /** @var \Application\Response\ResponseTypes\JsonResponse $response */
        $response = $container->getResults();

        $this->assertInstanceOf(\Application\Response\ResponseTypes\JsonResponse::class, $response);
        $this->assertEquals(json_encode($responseParameters), $response->getContent());
    }

    /**
     * @throws \Application\Router\RouteException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Application\View\Twig\TwigFactoryException
     * @throws \Response\ResponseTypes\RedirectResponseException
     */
    public function testShouldReturnOtherThanRouteErrorResponse()
    {
        $serviceContainerMockBuilder = $this->getServiceContainerMockBuilder();
        $serviceContainerMockBuilder->setRequestMock(
            m::mock(\Application\Service\Request\Request::class)
                ->shouldHaveReceived('getRequestUri')
                ->once()
                ->andReturn('/admin/json')
                ->getMock()
        );
        $responseParameters = [['exception' => 'FATAL ERROR ']];
        $errorResponse = new \Application\Response\ResponseTypes\ErrorResponse($responseParameters);

        $contextMock = m::mock(\Application\Context\Context::class)
            ->shouldHaveReceived('__invoke')
            ->once()
            ->getMock()
            ->shouldHaveReceived('getResults')
            ->once()
            ->andReturn($errorResponse)
            ->getMock();

        $viewMock = m::mock(\Application\View\View::class)
            ->shouldHaveReceived('render')
            ->getMock();

        $container = new \Test\Decorator\ContainerDecorator(
            $serviceContainerMockBuilder->build(),
            $contextMock,
            $viewMock
        );

        $container();

        /** @var \Application\Response\ResponseTypes\JsonResponse $response */
        $response = $container->getResults();

        $this->assertInstanceOf(\Application\Response\ResponseTypes\ErrorResponse::class, $response);
        $this->assertEquals($responseParameters, $response->getParameters());
    }
}