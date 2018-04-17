<?php

namespace Dynamicus\Image\Transformer\Plugin;

use Common\Entity\DataObject;
use Dynamicus\Image\Options;

/**
 * Class TransformerPluginInterface
 */
interface TransformerPluginInterface
{
    /**
     * @param DataObject $do
     * @param array      $options
     * @return \SplObjectStorage|Options[]
     */
    public function transform(DataObject $do, array $options): \SplObjectStorage;
}
