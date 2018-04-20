<?php

namespace Application\Config;

final class Config
{
    private static $config;

    private static $environment = '_test';

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
        self::loadFile('config' . self::$environment . '.php');
    }

    public static function loadFile($filename)
    {
        return self::$config = include dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . $filename;
    }
}