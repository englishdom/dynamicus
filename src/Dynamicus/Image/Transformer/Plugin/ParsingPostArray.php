<?php

namespace Dynamicus\Image\Transformer\Plugin;

use Dynamicus\Image\Options;

/**
 * Class ParsingPostArray
 */
class ParsingPostArray implements TransformerPluginInterface
{
    /**
     * @param array $options
     * @return \SplObjectStorage|Options[]
     */
    public function transform(array $options): \SplObjectStorage
    {
        $storage = new \SplObjectStorage();

        foreach ($options as $optionsRow) {
            $options = new Options();
            $options->setVariant('default');
            $options->setSize(
                explode('x', $optionsRow['size'])
            );
            $options->setCrop(
                explode('x', $optionsRow['crop'])
            );

            $storage->attach($options);
        }

        return $storage;
    }
}