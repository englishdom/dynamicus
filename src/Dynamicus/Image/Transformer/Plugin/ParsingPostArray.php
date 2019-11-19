<?php

namespace Dynamicus\Image\Transformer\Plugin;

use Common\Entity\DataObject;
use Common\Exception\BadRequestException;
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
            if (isset($optionsRow['center'])) {
                $options->setAutoResize(explode('x', $optionsRow['center']));
                list ($width, $height) = $this->getSizesFromConfig($options->getAutoResize(), $do);
            } elseif (isset($optionsRow['size'])) {
                $options->setSize(explode('x', $optionsRow['size']));
                $options->setCrop(explode('x', $optionsRow['crop']));
                list ($width, $height) = $this->getSizesFromConfig($options->getSize(), $do);
            } else {
                throw new BadRequestException('Invalid resize parameters!');
            }

            $options->setFileNameSizes([$width, $height]);
            $storage->attach($options);
        }

        return $storage;
    }

    /**
     * Поиск и получение размеров из конфига размеров.
     * Потому что имиджи могут быть с динамическими размерами.
     * Например 300x<любой размер>
     * @param array      $sizes
     * @param DataObject $do
     * @return array
     */
    protected function getSizesFromConfig(array $sizes, DataObject $do): array
    {
        $namespace = $do->getNamespace() ?? KEY_DEFAULT;
        /* поиск по точному соответствию размеров */
        foreach ($this->optionsSize[$namespace] as $configSizes) {
            if ($configSizes[WIDTH] == $sizes[0] && $configSizes[HEIGHT] == $sizes[1]) {
                return $sizes;
            }
        }
        /* Поиск по 1 пустому полю */
        foreach ($this->optionsSize[$namespace] as $configSizes) {
            if (($configSizes[WIDTH] == $sizes[0] && $configSizes[HEIGHT] === false)
            || ($configSizes[WIDTH] === false && $configSizes[HEIGHT] == $sizes[1])) {
                return [$configSizes[WIDTH], $configSizes[HEIGHT]];
            }
        }

        return $sizes;
    }
}
