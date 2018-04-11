<?php

namespace Dynamicus\Image;

use Common\Entity\ImageDataObject;

/**
 * Class ImageCreatorTrait
 * @package Dynamicus\Image
 */
trait ImageCreatorTrait
{
    protected function makeFileName(ImageDataObject $do, ?Options $options): string
    {
        if (!$options) {
            $fileName = sprintf(
                '%s.%s',
                $do->getEntityId(),
                $do->getExtension()
            );
        } else {
            $fileName = sprintf(
                '%s_%s_%s.%s',
                $do->getNamespace() == KEY_CONTENT ? time() : $do->getEntityId(),
                $options->getVariant(),
                implode('x', $options->getFileNameSizes()),
                $do->getExtension()
            );
        }
        return $fileName;
    }
}
