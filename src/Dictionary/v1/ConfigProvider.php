<?php

namespace Dictionary;

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
                Action\DictionaryAction::class => Factory\DictionaryActionFactory::class,
                Adapter\AdapterManager::class => Factory\AdapterFactory::class,
            ],
        ];
    }
}
