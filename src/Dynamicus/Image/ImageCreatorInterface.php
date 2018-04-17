<?php

namespace Dynamicus\Image;

use Common\Entity\DataObject;

/**
 * Class ImageManagerInterface
 * @package Dynamicus\Image
 */
interface ImageCreatorInterface
{
    public function process(DataObject $do, array $request);
}
