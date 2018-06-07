<?php

namespace Application\Service\Logger;

use Application\Config\Config;
use Application\Service\ServiceInterface;

class Logger implements ServiceInterface
{
    const LOGGER_FILE_SUFFIX = '-logs.log';

    private $config;
    private $loggers;

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
        $filename = $this->config['instances'][$logger]['dir'] . date('Y-m-d-') . $this->config['instances'][$logger]['name'] . self::LOGGER_FILE_SUFFIX;

        if(!file_exists($filename))
        {
            touch($filename);
        }

        if(!is_writable($filename))
        {
            throw new LoggerException(sprintf('Directory \'%s\' is not writable', $filename));
        }

        file_put_contents($filename, sprintf("[%s] %s: %s %s", date('Y-m-d-H-i-s') , $level, $message, PHP_EOL), FILE_APPEND);
    }

}