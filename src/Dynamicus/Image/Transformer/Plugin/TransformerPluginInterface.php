<?php

namespace Dynamicus\Image\Transformer\Plugin;

use Common\Entity\ImageDataObject;
use Dynamicus\Image\Options;

/**
 * Class TransformerPluginInterface
 */
interface TransformerPluginInterface
{
    /**
     * @param ImageDataObject $do
     * @param array           $options
     * @return \SplObjectStorage|Options[]
     */
    public function transform(ImageDataObject $do, array $options): \SplObjectStorage;
}
