<?php

namespace Audicus\Factory;

use Audicus\Action\ListAction;
use Common\Container\ConfigInterface;
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
        $redis = $container->get(\Redis::class);
        $config = $container->get(ConfigInterface::class);
        $adapter = $container->get(AdapterInterface::class);
        return new ListAction($redis, $config, $adapter);
    }
}
