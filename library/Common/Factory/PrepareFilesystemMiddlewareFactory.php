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
        /* Передается контейнер только потому, что нужно выбирать различные адаптеры */
        $config = $container->get(ConfigInterface::class);
        return new PrepareFilesystemMiddleware($container, $config);
   }
}