<?php

namespace Dynamicus\Image\Transformer\Plugin;

use Dynamicus\Image\Options;

/**
 * Class ParsingConfigArray
 */
class ParsingConfigArray implements TransformerPluginInterface
{
    public function transform(array $options): \SplObjectStorage
    {
        $storage = new \SplObjectStorage();

        foreach ($options as $variant => $optionsRow) {
            foreach ($optionsRow as $dimensions) {
                $options = new Options();
                $options->setVariant($variant);
                if (isset($dimensions[COMPRESSION_QUALITY])) {
                    $options->setQuality($dimensions[COMPRESSION_QUALITY]);
                }
                $options->setAutoResize([$dimensions[WIDTH], $dimensions[HEIGHT]]);

                $storage->attach($options);
            }
        }

        return $storage;
    }

}
