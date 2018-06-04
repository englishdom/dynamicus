<?php

namespace Common\Factory;

use League\Flysystem\Adapter\Local;
use Common\Container\ConfigInterface;
use Psr\Container\ContainerInterface;

/**
 * Class FilesystemLocalFSAdapterFactory
 * @package Common\Factory
 */
class FilesystemLocalFSAdapterFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        $rootPath = $config->get('images-path.root-path');
        return new Local($rootPath, 0);
    }
}
