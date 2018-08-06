<?php

namespace Audicus\Factory;

use Audicus\Action\ListAction;
use Common\Container\ConfigInterface;
use Common\Storage\StorageInterface;
use League\Flysystem\AdapterInterface;
use Psr\Container\ContainerInterface;

/**
 * Class ListActionFactory
 * @package Audicus\Factory
 */
class ListActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $storage = $container->get(StorageInterface::class);
        $config = $container->get(ConfigInterface::class);
        $adapter = $container->get(AdapterInterface::class);
        return new ListAction($storage, $config, $adapter);
    }
}
