<?php

namespace Dynamicus\Image\Processor;

use Common\Entity\File;
use Dynamicus\Image\Options;

/**
 * Class ProcessInterface
 * @package Dynamicus\Image
 */
interface ProcessorInterface
{
    public function process(File $imageFile, Options $options);
}
