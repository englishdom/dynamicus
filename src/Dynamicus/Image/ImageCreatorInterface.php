<?php

namespace Dynamicus\Image;

use Common\Entity\ImageDataObject;

/**
 * Class ImageManagerInterface
 * @package Dynamicus\Image
 */
interface ImageCreatorInterface
{
    public function process(ImageDataObject $do, array $request);
}
