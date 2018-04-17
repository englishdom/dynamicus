<?php

namespace Dynamicus\Image\Transformer\Plugin;

use Common\Entity\DataObject;
use Dynamicus\Image\Options;

/**
 * Class ParsingPostArray
 */
class ParsingPostArray implements TransformerPluginInterface
{
    const DEFAULT_NAMESPACE = 'default';
    /**
     * @var array
     */
    private $optionsSize;

    /**
     * ParsingPostArray constructor.
     * @param array $optionsSize
     */
    public function __construct(array $optionsSize)
    {
        $this->optionsSize = $optionsSize;
    }

    /**
     * @param DataObject $do
     * @param array      $options
     * @return \SplObjectStorage|Options[]
     */
    public function transform(DataObject $do, array $options): \SplObjectStorage
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

            $options->setFileNameSizes([$options->getSize()[0], $options->getSize()[1]]);
            $storage->attach($options);
        }

        return $storage;
    }
}
