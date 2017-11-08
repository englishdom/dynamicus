<?php

namespace Common\Factory;

use Common\Container\ConfigInterface;
use Common\Middleware\ShardingMiddleware;
use Interop\Container\ContainerInterface;

class ShardingMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        return new ShardingMiddleware($config);
    }
}
