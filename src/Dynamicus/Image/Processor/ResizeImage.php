<?php

namespace Dynamicus\Image\Processor;

use Common\Entity\ImageFile;
use Dynamicus\Image\Options;

/**
 * Class ResizeImage
 * @package Dynamicus\Image
 */
class ResizeImage extends AbstractImage implements ProcessorInterface
{
    public function process(ImageFile $imageFile, Options $options)
    {
        if ($options->getSize()) {
            $imagick = $this->getImagick($imageFile->getPath());
            $imagick->adaptiveResizeImage(
                $options->getSize()[0],
                $options->getSize()[1],
                true
            );
            $imagick->writeImage($imageFile->getPath());
            $imagick->clear();
            $imagick->destroy();
        }
    }
}
