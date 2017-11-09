<?php

namespace Dynamicus\Image\Transformer;

use Dynamicus\Image\Options;
use Dynamicus\Image\Transformer\Plugin\TransformerPluginInterface;

/**
 * Парсинг массива конфигов в объект ProcessorOptions
 * Class Transformer
 */
class Transformer implements TransformerInterface
{
    /**
     * @var TransformerPluginInterface
     */
    private $plugin;

    /**
     * @param array $options
     * @return \SplObjectStorage|Options[]
     */
    public function transform(array $options): \SplObjectStorage
    {
        return $this->getPlugin()->transform($options);
    }

    /**
     * @return TransformerPluginInterface
     */
    public function getPlugin(): TransformerPluginInterface
    {
        return $this->plugin;
    }

    /**
     * @param TransformerPluginInterface $plugin
     */
    public function setPlugin(TransformerPluginInterface $plugin)
    {
        $this->plugin = $plugin;
    }
}
