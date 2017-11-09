<?php

namespace Dynamicus\Image\Processor;

use Common\Entity\ImageFile;
use Dynamicus\Image\Options;

/**
 * Class CleanImage
 * @package Dynamicus\Image
 */
class CleanImage extends AbstractImage implements ProcessorInterface
{
    public function process(ImageFile $imageFile, Options $options)
    {
        $imagick = $this->getImagick($imageFile->getPath());
        $imagick->stripImage();
        $imagick->writeImage($imageFile->getPath());
        $imagick->clear();
        $imagick->destroy();
    }
}
