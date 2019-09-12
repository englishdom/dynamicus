<?php

namespace Common\Factory;

use Common\Container\ConfigInterface;
use Common\Middleware\DiagnosticsMiddleware;
use Interop\Container\ContainerInterface;

class DiagnosticsMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        $collection = new \SplObjectStorage();
        foreach ($config->get('diagnostics') as $checkerName => $checkerClass) {
            if (is_object($checkerClass)) {
                $collection->attach($checkerClass, $checkerName);
            } else {
                $collection->attach(new $checkerClass($container), $checkerName);
            }
        }
        return new DiagnosticsMiddleware($collection);
    }
}