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
        foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir), \RecursiveIteratorIterator::SELF_FIRST) as $fileInfo)
        {
            if($fileInfo->isFile() && $this->isResource($fileInfo))
            {
                $dest = str_replace(
                    $config->twig['loader']['templates'],
                    sprintf('%s/assets', $config->web_dir),
                    str_replace('/assets', '', $fileInfo->getPathname())
                );

                if(!file_exists($dest))
                {
                    $destDirectory = dirname($dest);

                    if(!file_exists($destDirectory))
                    {
                        $status = @mkdir($destDirectory, 0777, true);

                        if(!$status)
                        {
                            throw new CommandException(sprintf('Cannot create "%s" dir: "%s"', $destDirectory, $status));
                        }
                    }

                    if(!is_writable($destDirectory))
                    {
                        throw new CommandException(sprintf('Directory "%s" must be writable', $destDirectory));
                    }
                    touch($dest);
                    chmod($dest, 0777);
                }

                if(!is_writable($dest))
                {
                    throw new CommandException(sprintf('Cannot write to file "%s"', $dest));
                }

                copy($fileInfo->getPathname(), $dest);

                $this->addOutput(sprintf('Caching file %s', $dest) . PHP_EOL);
            }
        }

        return $this->sendOutput();
    }

    private function isResource(\SplFileInfo $fileInfo): bool
    {
        return in_array($fileInfo->getExtension(), self::CACHEABLES_EXTENSIONS);
    }
}