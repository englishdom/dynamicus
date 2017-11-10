<?php

namespace Common\Factory;

use Common\Container\ConfigInterface;
use League\Flysystem\Adapter\Local;
use Psr\Container\ContainerInterface;

/**
 * Class FilesystemAdapterFactory
 * @package Common\Factory
 */
class FilesystemAdapterFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        return new Local($config->get('images-path.root-path'));
    }
}
