<?php

namespace Dynamicus\Image\Transformer\Plugin;

use Common\Entity\ImageDataObject;
use Dynamicus\Image\Options;

/**
 * Class ParsingPostArray
 */
class ParsingPostArray implements TransformerPluginInterface
{
    const DEFAULT_NAMESPACE = 'default';

    /**
     * @param ImageDataObject $do
     * @param array           $options
     * @return \SplObjectStorage|Options[]
     */
    public function transform(ImageDataObject $do, array $options): \SplObjectStorage
    {
        $storage = new \SplObjectStorage();

        foreach ($options as $optionsRow) {
            $options = new Options();
            if ($do->getNamespace()) {
                $options->setVariant($do->getNamespace());
            } else {
                $options->setVariant(self::DEFAULT_NAMESPACE);
            }
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
