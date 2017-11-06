<?php

namespace Dinamicus;

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
            'factories' => [
                Action\ListAction::class => Factory\ListActionFactory::class,
            ],
        ];
    }
}
