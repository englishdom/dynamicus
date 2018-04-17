<?php

namespace Dynamicus\Image\Processor;

use Common\Entity\File;
use Dynamicus\Image\Options;

/**
 * Class ResizeImage
 * @package Dynamicus\Image
 */
class ResizeImage extends AbstractImage implements ProcessorInterface
{
    public function process(File $imageFile, Options $options)
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
