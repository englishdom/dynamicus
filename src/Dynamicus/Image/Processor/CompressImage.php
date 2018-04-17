<?php

namespace Dynamicus\Image\Processor;

use Common\Entity\File;
use Dynamicus\Image\Options;

/**
 * Class CompressImage
 * @package Dynamicus\Image
 */
class CompressImage extends AbstractImage implements ProcessorInterface
{
    public function process(File $imageFile, Options $options)
    {
        if ($options->getQuality()) {
            $imagick = $this->getImagick($imageFile->getPath());
            $imagick->setImageCompressionQuality($options->getQuality());
            $imagick->writeImage($imageFile->getPath());
            $imagick->clear();
            $imagick->destroy();
        }
    }
}
