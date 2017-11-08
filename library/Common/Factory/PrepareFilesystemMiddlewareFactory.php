<?php

namespace Common\Factory;

use Common\Container\ConfigInterface;
use Common\Middleware\PrepareFilesystemMiddleware;
use Psr\Container\ContainerInterface;

/**
 * Class PrepareFilesystemMiddlewareFactory
 * @package Common\Factory
 */
class PrepareFilesystemMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        return new PrepareFilesystemMiddleware($config);
    }
}
