<?php

namespace Audicus;

/**
 * Class ConfigProvider
 * @package Audicus
 */
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
                Middleware\ShardingMiddleware::class => Factory\ShardingMiddlewareFactory::class,
                Middleware\GenerateAudioMiddleware::class => Factory\GenerateAudioMiddlewareFactory::class,
                Action\PostAction::class => Factory\PostActionFactory::class,
            ]
        ];
    }
}
