<?php

namespace Application\Console\Command\Command\Cache;

use Application\Console\Command\Command;
use Application\Console\Command\CommandException;
use Application\Console\Command\CommandParameters;
use Application\Response\ResponseTypes\ConsoleResponse;

class Build extends Command
{
    const CACHEABLES_EXTENSIONS = [
        'css',
        'js',
        'png',
        'jpg',
        'jpeg',
        'gif',
    ];

    /**
     * @return ConsoleResponse
     * @throws CommandException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function execute(): ConsoleResponse
    {
        $config = $this->event->getServiceContainer()->getService('config');
        $dir = $config->twig['loader']['templates'] . DIRECTORY_SEPARATOR;

        /** @var \SplFileInfo $fileInfo */
        foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST) as $fileInfo)
        {
            if($fileInfo->isFile() && $this->isResource($fileInfo))
            {
                $cachePath = str_replace(
                    $config->twig['loader']['templates'],
                    sprintf('%s/assets', $config->web_dir),
                    str_replace('/assets', '', $fileInfo->getPathname())
                );

                if(!file_exists($cachePath))
                {
                    $cacheDirectory = dirname($cachePath);

                    if(!file_exists($cacheDirectory))
                    {
                        if(!$this->createDirectory($cacheDirectory, 0777, true))
                        {
                            throw new CommandException(sprintf('Cannot create dir directory "%s"', $cacheDirectory));
                        }
                    }

                    if(!is_writable($cacheDirectory))
                    {
                        throw new CommandException(sprintf('Directory "%s" must be writable', $cacheDirectory));
                    }

                    touch($cachePath);
                    chmod($cachePath, 0777);
                }

                if(!is_writable($cachePath))
                {
                    throw new CommandException(sprintf('Cannot write to file "%s"', $cachePath));
                }

                copy($fileInfo->getPathname(), $cachePath);

                $this->addOutput(sprintf('Caching file %s', $cachePath) . PHP_EOL);
            }
        }

        return $this->sendOutput();
    }

    private function isResource(\SplFileInfo $fileInfo): bool
    {
        return in_array($fileInfo->getExtension(), self::CACHEABLES_EXTENSIONS);
    }

    private function createDirectory($dir, $perms, $recursive): bool
    {
        return @mkdir($dir, $perms, $recursive);
    }
}