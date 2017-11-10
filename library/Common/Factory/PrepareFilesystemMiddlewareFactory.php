<?php

namespace Common\Factory;

use Common\Middleware\PrepareFilesystemMiddleware;
use League\Flysystem\AdapterInterface;
use Psr\Container\ContainerInterface;

/**
 * Class PrepareFilesystemMiddlewareFactory
 * @package Common\Factory
 */
class PrepareFilesystemMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new PrepareFilesystemMiddleware($container->get(AdapterInterface::class));
    }
}
