<?php

namespace Dictionary\Factory;

use Common\Container\ConfigInterface;
use Dictionary\Adapter\AdapterManager;
use Psr\Container\ContainerInterface;

class AdapterFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        return new AdapterManager($config);
    }
}
