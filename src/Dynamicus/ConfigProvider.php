<?php

namespace Dynamicus;

use Dynamicus\Factory;
use Dynamicus\Image\ImageCreatorInterface;
use Dynamicus\Middleware\ProcessImageMiddleware;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    /**
     * Returns the container dependencies
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            'factories'  => [
                ImageCreatorInterface::class => Factory\ImageCreatorFactory::class,
                ProcessImageMiddleware::class => Factory\ProcessImageMiddlewareFactory::class,
            ]
        ];
    }
}
