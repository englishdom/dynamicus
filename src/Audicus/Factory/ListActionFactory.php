<?php

namespace Audicus\Factory;

use Audicus\Action\ListAction;
use Psr\Container\ContainerInterface;


/**
 * Class ListActionFactory
 * @package Audicus\Factory
 */
class ListActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $redis = $container->get(\Redis::class);
        return new ListAction($redis);
    }
}
