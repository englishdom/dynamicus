<?php

namespace Common\Factory;

use Common\Container\ConfigInterface;
use Common\Middleware\DomainMiddleware;
use Psr\Container\ContainerInterface;

/**
 * Class PrepareFilesystemMiddlewareFactory
 * @package Common\Factory
 */
class DomainMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        return new DomainMiddleware($config);
   }
}