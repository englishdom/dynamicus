<?php

namespace Dynamicus\Image\Transformer\Plugin;

use Common\Entity\DataObject;
use Dynamicus\Image\Options;

/**
 * Class ParsingConfigArray
 */
class ParsingConfigArray implements TransformerPluginInterface
{
    public function transform(DataObject $do, array $options): \SplObjectStorage
    {
        $storage = new \SplObjectStorage();

        foreach ($options as $variant => $optionsRow) {
            /* Если оригинал то нет размеров */
            if ($variant == KEY_ORIGINAL) {
                return $storage;
            }

            /* Если в конфиге размер не массив */
            if (!is_array($optionsRow)) {
                $options = new Options();
                $options->setVariant($variant);
                $storage->attach($options);
                continue;
            }

            foreach ($optionsRow as $dimensions) {
                if (!is_array($dimensions)) {
                    continue;
                }

                $options = new Options();
                $options->setVariant($variant);
                if (isset($dimensions[QUALITY])) {
                    $options->setQuality($dimensions[QUALITY]);
                }
                $options->setAutoResize([$dimensions[WIDTH], $dimensions[HEIGHT]]);
                $options->setFileNameSizes([$dimensions[WIDTH], $dimensions[HEIGHT]]);
                $storage->attach($options);
            }
        }

        return $storage;
    }
}
