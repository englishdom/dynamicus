<?php

namespace Dynamicus\Image\Transformer;

use Common\Entity\ImageDataObject;
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
     * @param ImageDataObject $do
     * @param array           $options
     * @return \SplObjectStorage|Options[]
     */
    public function transform(ImageDataObject $do, array $options): \SplObjectStorage
    {
        return $this->getPlugin()->transform($do, $options);
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
