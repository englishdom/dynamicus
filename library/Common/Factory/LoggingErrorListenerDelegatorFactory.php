<?php

namespace Common\Factory;

use Common\Container\ConfigInterface;
use Common\Container\LoggingErrorListener;
use Psr\Container\ContainerInterface;
use Zend\Log\LoggerInterface;
use Zend\Stratigility\Middleware\ErrorHandler;

class LoggingErrorListenerDelegatorFactory
{
    /**
     * @param ContainerInterface $container
     * @param string $name
     * @param callable $callback
     * @return ErrorHandler
     */
    public function __invoke(ContainerInterface $container, $name, callable $callback)
    {
        $listener = new LoggingErrorListener(
            $container->get(ConfigInterface::class),
            $container->get(LoggerInterface::class)
        );
        /* @var $repository ErrorHandler */
        $repository = $callback();
        $repository->attachListener($listener);
        return $repository;
    }
}
