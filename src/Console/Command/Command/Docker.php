<?php

namespace Application\Console\Command\Command;

use Application\Console\Command\ConsoleExecutable;

class Docker extends ConsoleExecutable
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
        $this->execute('docker-compose up -d');
    }

    public function run()
    {
        $this->execute('docker-compose up --build -d');
    }

    public function build()
    {
        $this->execute('docker-compose up --build -d --force-recreate');
    }

    public function stop()
    {
        $this->execute('docker-compose down');
    }

    public function ssh($dockerName = null)
    {
        $dockerName = $dockerName ? $dockerName : self::DOCKER_CONTAINER_NAME;
        $this->execute('docker exec -it -u devops %s /bin/bash', [$dockerName]);
    }
}