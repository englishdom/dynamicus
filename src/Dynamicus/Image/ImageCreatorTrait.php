<?php

namespace Dynamicus\Image;

use Common\Entity\ImageDataObject;

/**
 * Class ImageCreatorTrait
 * @package Dynamicus\Image
 */
trait ImageCreatorTrait
{
    protected function makeFileName(ImageDataObject $do, Options $options): string
    {
        $fileName = sprintf(
            '%s_%s_%s.%s',
            $do->getEntityId(),
            $options->getVariant(),
            $options->getSize() ? implode('x', $options->getSize()) : implode('x', $options->getAutoResize()),
            $do->getExtension()
        );
        return $fileName;
    }
}
