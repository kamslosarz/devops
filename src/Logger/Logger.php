<?php

namespace Application\Logger;

use Application\Config\Config;
use function Couchbase\fastlzCompress;

final class Logger
{
    const LOGGER_FILE_SUFFIX = '-app-logs.log';

    private $logDirectory;
    private $logName;
    private $loggers;

    public function __construct($loggerName)
    {
        $loggerConfig = $this->getConfig($loggerName);

        $this->logDirectory = $loggerConfig['dir'];
        $this->logName = $loggerName;
    }

    public function log($message, $level)
    {
        $date = date('Y-m-d');
        $filename = $this->logDirectory . $date . self::LOGGER_FILE_SUFFIX;

        if (!file_exists($filename)) {
            touch($filename);
        }

        file_put_contents($filename, sprintf(
            "[%s] %s: %s %s"
            , $date, $level, $message, PHP_EOL), FILE_APPEND);
    }


    private function getConfig($loggerName)
    {
        if (isset($this->loggers[$loggerName])) {

            return $this->loggers[$loggerName];
        } else {
            $this->loggers[$loggerName] = Config::get('logger')[$loggerName];

            return $this->getConfig($loggerName);
        }
    }

}