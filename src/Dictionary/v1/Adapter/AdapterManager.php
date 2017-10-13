<?php

namespace Dictionary\Adapter;

use Common\Container\ConfigInterface;
use Common\Exception\RuntimeException;

class AdapterManager
{
    const STARDICT = 'stardict';
    const APRESYAN = 'apresyan';

    /**
     * @var ConfigInterface
     */
    private $config;

    private $adapters = [
        self::STARDICT => StardictAdapter::class,
        self::APRESYAN => ApresyanAdapter::class,
    ];

    /**
     * AdapterManager constructor.
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }


    public function getAdapter($adapterName): AdapterInterface
    {
        switch ($adapterName) {
            case self::STARDICT:
                $path = $this->config->get('dictionary.path.stardict');
                $adapter = $this->adapters[self::STARDICT];
                break;
            case self::APRESYAN:
                $path = $this->config->get('dictionary.path.apresyan');
                $adapter = $this->adapters[self::APRESYAN];
                break;
            default:
                throw new RuntimeException('Dictionary does not exist!');
        }

        return new $adapter($path);
    }
}
