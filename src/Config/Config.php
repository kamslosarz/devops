<?php

namespace Application\Config;

use Application\Application;

abstract class Config
{
    private static $config;
    private static $configs = [];

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
        if(!isset(self::$configs[$filename]))
        {
            self::$configs[$filename] = include dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . $filename;
        }

        return self::$configs[$filename];
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

        throw new ConfigException(sprintf('File \'%s\' not exists', $filename));
    }

}