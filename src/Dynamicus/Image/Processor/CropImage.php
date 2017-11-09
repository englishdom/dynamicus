<?php

namespace Dynamicus\Image\Processor;

use Common\Entity\ImageFile;
use Dynamicus\Image\Options;

/**
 * Class CropImage
 * @package Dynamicus\Image
 */
class CropImage extends AbstractImage implements ProcessorInterface
{
    public function process(ImageFile $imageFile, Options $options)
    {
        if ($options->getCrop() && $options->getSize()) {
            $imagick = $this->getImagick($imageFile->getPath());
            $imagick->cropImage(
                $options->getSize()[0], // Width
                $options->getSize()[1], // Height
                $options->getCrop()[0], // X
                $options->getCrop()[1] // Y
            );
            $imagick->writeImage($imageFile->getPath());
            $imagick->clear();
            $imagick->destroy();
        }
    }

}
