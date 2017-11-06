<?php

namespace Dinamicus\Factory;

use Common\Container\ConfigInterface;
use Dinamicus\Action\ListAction;
use Psr\Container\ContainerInterface;

class ListActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        return new ListAction($config);
    }
}
