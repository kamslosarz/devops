<?php

namespace Application\Service\ServiceContainer;


use Application\Config\Config;
use Application\Service\ServiceParameters;

class ServiceContainer
{
    private $serviceContainer = [];

    /**
     * @param $serviceName
     * @return mixed
     * @throws ServiceContainerException
     */
    public function getService($serviceName)
    {
        if(isset($this->serviceContainer[$serviceName]))
        {
            return $this->serviceContainer[$serviceName];
        }

        return $this->loadService($serviceName);
    }

    /**
     * @param $serviceName
     * @return mixed
     * @throws ServiceContainerException
     */
    private function loadService($serviceName)
    {
        $serviceMap = Config::loadFlatFile(Config::get('servicesMapFIle'));

        if(!isset($serviceMap[$serviceName]))
        {
            throw new ServiceContainerException(sprintf('Service \'%s\' not found', $serviceName));
        }

        $parameters = [];
        $serviceParameters = new ServiceParameters($serviceMap[$serviceName]);

        foreach($serviceParameters->getParameters() as $parameterName => $parameter)
        {
            if(is_string($parameter) && substr($parameter, 0, 1) === '@')
            {
                $parameters[] = $this->serviceContainer[$serviceName] = $this->getService(ltrim($parameter, '@'));
            }
            else
            {
                $parameters[] = [$parameterName => $parameter];
            }
        }

        $this->serviceContainer[$serviceName] = (new ServiceResolver($serviceParameters->getClassname(), $parameters))();

        return $this->getService($serviceName);
    }
}