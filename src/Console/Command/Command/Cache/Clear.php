<?php

namespace Application\Console\Command\Command\Cache;

use Application\Console\Command\Command;
use Application\Console\Command\CommandParameters;
use Application\Response\ResponseTypes\ConsoleResponse;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;
use Symfony\Component\Finder\SplFileInfo;

class Clear extends Command
{
    /**
     * @return ConsoleResponse
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function execute(): ConsoleResponse
    {
        $this->deleteCacheDirectory($this->event->getServiceContainer()->getService('config')->web_dir);

        return $this->addOutput('Cache cleared')->sendOutput();
    }

    /**
     * @param $dir
     */
    protected function deleteCacheDirectory($dir): void
    {
        /** @var SplFileInfo $fileInfo */
        foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST) as $fileInfo)
        {
            if($fileInfo->isFile())
            {
                unlink($fileInfo->getPathname());
            }
            elseif($fileInfo->isDir())
            {
                rmdir($fileInfo->getPathname());
            }
        }
    }
}