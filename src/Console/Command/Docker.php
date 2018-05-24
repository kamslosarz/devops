<?php

namespace Application\Console\Command;

class Docker extends Command
{
    const DOCKER_CONTAINER_NAME = 'devops_www_1';

    public function isValid()
    {
        return true;
    }

    public function restart()
    {
        $this->stop();
        $this->run();
    }

    public function up()
    {
        passthru('docker-compose up -d');
    }

    public function run()
    {
        passthru('docker-compose up --build -d');
    }

    public function build()
    {
        passthru('docker-compose up --build -d --force-recreate');
    }

    public function stop()
    {
        passthru('docker-compose down');
    }

    public function root($dockerName = null)
    {
        $dockerName = $dockerName ? $dockerName : self::DOCKER_CONTAINER_NAME;
        passthru(sprintf('docker exec -it --user root %s /bin/bash', $dockerName));
    }
}