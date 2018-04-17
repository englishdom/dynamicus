<?php

namespace Dynamicus\Image\Processor;

use Common\Entity\File;
use Dynamicus\Image\Options;

/**
 * Class CleanImage
 * @package Dynamicus\Image
 */
class CleanImage extends AbstractImage implements ProcessorInterface
{
    public function process(File $imageFile, Options $options)
    {
        $imagick = $this->getImagick($imageFile->getPath());
        $imagick->stripImage();
        $imagick->writeImage($imageFile->getPath());
        $imagick->clear();
        $imagick->destroy();
    }
}
