<?php

namespace Application\Config;

use Application\Application;

final class Config
{
    private static $config;

    public static function get($key)
    {
        if(!is_array(self::$config))
        {
            self::load();
        }

        return self::$config[$key];
    }

    public static function reload()
    {
        self::load();
    }

    public static function load()
    {
        self::$config = self::loadFile('config' . Application::getEnvironment() . '.php');
    }

    public static function loadFile($filename)
    {
        return include dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * @param $filename
     * @return mixed
     * @throws ConfigException
     */
    public static function loadFlatFile($filename)
    {
        if(file_exists($filename))
        {
            return include $filename;
        }

        throw new ConfigException(sprintf('File "%s" not exists', $filename));
    }

}