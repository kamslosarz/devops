<?php

namespace Application\Service\Logger;

use Application\Application;
use Application\Service\ServiceInterface;

class Logger implements ServiceInterface
{
    const LOGGER_FILE_SUFFIX = '-logs.log';

    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @param $logger
     * @param $message
     * @param string $level
     * @throws LoggerException
     */
    public function log($logger, $message, $level = LoggerLevel::INFO)
    {
        if(!Application::isTest())
        {
            $filename = $this->getFilename($logger);

            if(!is_writable(dirname($filename)))
            {
                throw new LoggerException(sprintf('Directory \'%s\' is not writable', dirname($filename)));
            }

            if(!file_exists($filename))
            {
                touch($filename);
            }

            file_put_contents($filename, sprintf("[%s] %s: %s %s", date('Y-m-d-H-i-s'), $level, $message, PHP_EOL), FILE_APPEND);
        }
    }

    public function getFilename($logger)
    {
        return $this->config['instances'][$logger]['dir'] . date('Y-m-d-') . $this->config['instances'][$logger]['name'] . self::LOGGER_FILE_SUFFIX;
    }
}