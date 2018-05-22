<?php

namespace Dynamicus\Factory;

use Common\Container\ConfigInterface;
use Dynamicus\Action\PostAction;
use Psr\Container\ContainerInterface;

/**
 * Class PostActionFactory
 * @package Dynamicus\Factory
 */
class PostActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        return new PostAction($config);
    }
}
