<?php

namespace Application\Service\ServiceContainer;


use Application\Config\Config;
use Application\Service\ServiceParameters;
use Application\Service\ServiceContainer\ServiceResolver;

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
        $serviceMap = Config::loadFile('serviceMap.php');

        if(!isset($serviceMap[$serviceName]))
        {
            throw new ServiceContainerException(sprintf('Service \'%s\' not found', $serviceName));
        }

        $parameters = [];
        $serviceParameters = new ServiceParameters($serviceMap[$serviceName]);


        foreach($serviceParameters->getParameters() as $parameter)
        {
            if(substr($parameter, 0, 1) === '@')
            {
                $parameters[] = $this->serviceContainer[$serviceName] = $this->getService(ltrim($parameter, '@'));
            }
            else
            {
                $parameters[] = $parameter;
            }
        }

        $this->serviceContainer[$serviceName] = (new ServiceResolver($serviceParameters->getClassname(), $parameters))();

        return $this->getService($serviceName);
    }
}