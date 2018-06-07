<?php

namespace Audicus\Factory;

use Audicus\Action\PostAction;
use Common\Container\ConfigInterface;
use Psr\Container\ContainerInterface;

/**
 * Class PostActionFactory
 * @package Audicus\Factory
 */
class PostActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        return new PostAction($config);
    }
}
