<?php

namespace Dynamicus\Factory;

use Dynamicus\Action\TestLogAction;
use Psr\Container\ContainerInterface;
use Zend\Log\LoggerInterface;

/**
 * Class SearchActionFactory
 * @package Dynamicus\Factory
 */
class TestLogActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new TestLogAction(
            $container->get(LoggerInterface::class)
        );
    }
}
