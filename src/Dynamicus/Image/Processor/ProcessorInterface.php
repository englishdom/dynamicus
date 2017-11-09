<?php

namespace Dynamicus\Image\Processor;

use Common\Entity\ImageFile;
use Dynamicus\Image\Options;

/**
 * Class ProcessInterface
 * @package Dynamicus\Image
 */
interface ProcessorInterface
{
    public function process(ImageFile $imageFile, Options $options);
}
