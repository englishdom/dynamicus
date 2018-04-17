<?php

namespace Dynamicus\Image\Processor;

use Common\Entity\File;
use Dynamicus\Image\Options;

/**
 * Class CropImage
 * @package Dynamicus\Image
 */
class CropImage extends AbstractImage implements ProcessorInterface
{
    public function process(File $imageFile, Options $options)
    {
        if ($options->getCrop()) {
            $imagick = $this->getImagick($imageFile->getPath());
            $sizes = $this->getCropSize($options->getCrop());
            $imagick->cropImage(
                $sizes[0], // Width
                $sizes[1], // Height
                $options->getCrop()[0], // X
                $options->getCrop()[1] // Y
            );
            $imagick->writeImage($imageFile->getPath());
            $imagick->clear();
            $imagick->destroy();
        }
    }

    /**
     * Получение ширины и высоты для кропа
     * @param array $crop
     * @return array
     */
    private function getCropSize(array $crop)
    {
        $width = $crop[2] - $crop[0];
        $height = $crop[3] - $crop[1];

        return [
            $width,
            $height
        ];
    }
}
