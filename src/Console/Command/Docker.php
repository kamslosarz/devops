<?php

namespace Application\Console\Command;

class Docker extends Command
{
    const DOCKER_CONTAINER_NAME = 'devops_www_1';

    public function restart()
    {
        $this->stop();
        $this->run();
    }

    public function run()
    {
        passthru('sudo docker-compose up --build -d');
    }

    public function build(){
        passthru('sudo docker-compose up --build -d --force-recreate');
    }

    public function stop()
    {
        passthru('sudo docker-compose down');
    }

    public function root($dockerName = null)
    {
        $dockerName = $dockerName ? $dockerName : self::DOCKER_CONTAINER_NAME;
        passthru(sprintf('sudo docker exec -it --user root %s /bin/bash', $dockerName));
    }
}