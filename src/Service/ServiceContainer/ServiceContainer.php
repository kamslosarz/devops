<?php

namespace Application\Service\ServiceContainer;

use Application\Service\ServiceInterface;
use Application\Service\ServiceParameters;

class ServiceContainer
{
    private $serviceContainer = [];
    private $servicesMap;

    public function __construct($servicesMap)
    {
        $this->servicesMap = $servicesMap;
    }

    /**
     * @param $serviceName
     * @return mixed
     * @throws ServiceContainerException
     */
    public function getService($serviceName): ServiceInterface
    {
        if(!$this->serviceExists($serviceName))
        {
            throw new ServiceContainerException(sprintf('Service \'%s\' not found', $serviceName));
        }

        if(isset($this->serviceContainer[$serviceName]))
        {
            return $this->serviceContainer[$serviceName];
        }

        $this->loadService($serviceName);

        return $this->getService($serviceName);
    }

    /**
     * @param $serviceName
     * @return mixed
     * @throws ServiceContainerException
     */
    private function loadService($serviceName): void
    {
        $parameters = [];
        $serviceParameters = new ServiceParameters($this->servicesMap[$serviceName]);

        foreach($serviceParameters->getParameters() as $parameter)
        {
            if(is_string($parameter) && substr($parameter, 0, 1) === '@')
            {
                $parameters[] = $this->serviceContainer[$serviceName] = $this->getService(ltrim($parameter, '@'));
            }
            else
            {
                $parameters[] = $parameter;
            }
        }

        $this->serviceContainer[$serviceName] = (new ServiceResolver($serviceParameters->getClassname(), $parameters))();
    }

    private function serviceExists($serviceName): bool
    {
        return isset($this->servicesMap[$serviceName]);
    }
}