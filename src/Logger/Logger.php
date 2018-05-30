<?php

namespace Application\Logger;

use Application\Config\Config;

final class Logger
{
    const LOGGER_FILE_SUFFIX = '-logs.log';

    private $logDirectory;
    private $logName;
    private $loggers;

    public function __construct($loggerName)
    {
        $loggerConfig = $this->getConfig($loggerName);

        $this->logDirectory = $loggerConfig['dir'];
        $this->logName = $loggerConfig['name'];
    }

    /**
     * @param $message
     * @param $leveli-s
     * @throws LoggerException
     */
    public function log($message, $level = LoggerLevel::INFO)
    {
        $filename = $this->logDirectory . date('Y-m-d-') . $this->logName . self::LOGGER_FILE_SUFFIX;

        if(!file_exists($filename))
        {
            touch($filename);
        }

        if(!is_writable($filename))
        {
            throw new LoggerException(sprintf('Directory "%s" is not writable', $filename));
        }

        file_put_contents($filename, sprintf("[%s] %s: %s %s", date('Y-m-d-H-i-s') , $level, $message, PHP_EOL), FILE_APPEND);
    }


    private function getConfig($loggerName)
    {
        if(isset($this->loggers[$loggerName]))
        {
            return $this->loggers[$loggerName];
        }
        else
        {
            $this->loggers[$loggerName] = isset(Config::get('logger')[$loggerName])? Config::get('logger')[$loggerName] : [];

            return $this->loggers[$loggerName];
        }
    }

}