<?php

namespace Dynamicus\Factory;

use Common\Container\ConfigInterface;
use Dynamicus\Middleware\CheckImageSizeMiddleware;
use Psr\Container\ContainerInterface;

/**
 * Class CheckImageSizeMiddlewareFactory
 * @package Dynamicus\Factory
 */
class CheckImageSizeMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        return new CheckImageSizeMiddleware($config);
    }
}
