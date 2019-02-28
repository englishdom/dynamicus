<?php

namespace Common\Factory;

use Psr\Container\ContainerInterface;
use Common\Middleware\ExceptionLoggingMiddleware;
use Zend\Log\LoggerInterface;

class ExceptionLoggingMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new ExceptionLoggingMiddleware(
            $container->get(LoggerInterface::class)
        );
    }
}