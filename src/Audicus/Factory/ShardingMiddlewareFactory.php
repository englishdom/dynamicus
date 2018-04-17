<?php

namespace Audicus\Factory;

use Audicus\Middleware\ShardingMiddleware;
use Common\Container\ConfigInterface;
use Psr\Container\ContainerInterface;

/**
 * Class ShardingMiddlewareFactory
 * @package Audicus\Factory
 */
class ShardingMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        return new ShardingMiddleware($config);
    }
}
