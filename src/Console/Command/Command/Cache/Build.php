<?php

namespace Application\Console\Command\Command\Cache;

use Application\Config\Config;
use Application\Console\Command\Command;
use Application\Console\Command\CommandException;
Use Application\Console\Command\Command\CommandParameters;

class Build extends Command
{
    /**
     * @param CommandParameters $commandParameters
     * @return $this
     * @throws CommandException
     */
    public function execute(CommandParameters $commandParameters)
    {
        $template = Config::get('twig')['loader']['templates'] . DIRECTORY_SEPARATOR;
        $templates = [
            $template . 'admin' . DIRECTORY_SEPARATOR . 'assets',
            $template . 'assets'
        ];

        foreach($templates as $dir)
        {
            /** @var \SplFileInfo $fileInfo */
            foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir), \RecursiveIteratorIterator::SELF_FIRST) as $fileInfo)
            {
                if($fileInfo->isFile())
                {
                    $dest = str_replace(
                        Config::get('twig')['loader']['templates'],
                        sprintf('%s/assets', Config::get('web_dir')),
                        str_replace('/assets', '', $fileInfo->getPathname())
                    );
                    if(!is_file($dest))
                    {
                        $destDirectory = dirname($dest);
                        if(!is_dir($destDirectory))
                        {
                            $status = mkdir($destDirectory, 0777, true);

                            if(!$status)
                            {
                                throw new CommandException(sprintf('Cannot create \'%s\' dir \'%s\'', $destDirectory, $status));
                            }
                        }

                        if(!is_writable($destDirectory))
                        {
                            throw new CommandException(sprintf('Directory \'%s\' must be writable', $destDirectory));
                        }

                        touch($dest);
                        chmod($dest, 0777);
                    }

                    if(!is_writable($dest))
                    {
                        throw new CommandException(sprintf('Cannot write to file \'%s\'', $dest));
                    }

                    copy($fileInfo->getPathname(), $dest);

                    $this->addOutput(sprintf('Caching file %s', $dest));
                }
            }
        }

        return $this->sendOutput();
    }

    public function isValid(CommandParameters $commandParameters)
    {
        return true;
    }
}