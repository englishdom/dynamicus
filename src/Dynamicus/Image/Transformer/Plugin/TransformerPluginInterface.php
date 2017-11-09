<?php

namespace Dynamicus\Image\Transformer\Plugin;

use Dynamicus\Image\Options;

/**
 * Class TransformerPluginInterface
 */
interface TransformerPluginInterface
{
    /**
     * @param array $options
     * @return \SplObjectStorage|Options[]
     */
    public function transform(array $options): \SplObjectStorage;
}
