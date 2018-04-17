<?php

namespace Dynamicus\Image\Transformer;

use Common\Entity\DataObject;
use Dynamicus\Image\Options;
use Dynamicus\Image\Transformer\Plugin\TransformerPluginInterface;

/**
 * Class TransformerInterface
 */
interface TransformerInterface
{

    /**
     * @return TransformerPluginInterface
     */
    public function getPlugin(): TransformerPluginInterface;

    /**
     * @param TransformerPluginInterface $plugin
     */
    public function setPlugin(TransformerPluginInterface $plugin);

    /**
     * @param DataObject $do
     * @param array      $options
     * @return \SplObjectStorage|Options[]
     */
    public function transform(DataObject $do, array $options): \SplObjectStorage;
}
