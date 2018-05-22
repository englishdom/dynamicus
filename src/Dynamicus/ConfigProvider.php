<?php

namespace Dynamicus;

use Dynamicus\Action\ListAction;
use Dynamicus\Action\PostAction;
use Dynamicus\Action\SearchAction;
use Dynamicus\Action\TestLogAction;
use Dynamicus\Factory;
use Dynamicus\Image\ImageCreatorInterface;
use Dynamicus\Image\Search\SearchAdapterInterface;
use Dynamicus\Middleware\CheckImageSizeMiddleware;
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
                CheckImageSizeMiddleware::class => Factory\CheckImageSizeMiddlewareFactory::class,
                ListAction::class => Factory\ListActionFactory::class,
                PostAction::class => Factory\PostActionFactory::class,
                SearchAction::class => Factory\SearchActionFactory::class,
                /* Устанавливается только 1 адаптер GoogleSearchAdapter. */
                SearchAdapterInterface::class => Factory\SearchAdapterFactory::class,
                TestLogAction::class => Factory\TestLogActionFactory::class,
            ]
        ];
    }
}
