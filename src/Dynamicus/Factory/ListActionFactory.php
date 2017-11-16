<?php

namespace Dynamicus\Factory;

use Common\Container\ConfigInterface;
use Dynamicus\Action\ListAction;
use Psr\Container\ContainerInterface;

/**
 * Class ListActionFactory
 * @package Dynamicus\Factory
 */
class ListActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        return new ListAction($config);
    }
}
