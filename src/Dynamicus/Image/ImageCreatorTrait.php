<?php

namespace Dynamicus\Image;

use Common\Entity\DataObject;

/**
 * Class ImageCreatorTrait
 * @package Dynamicus\Image
 */
trait ImageCreatorTrait
{
    protected function makeFileName(DataObject $do, ?Options $options): string
    {
        if (!$options) {
            $fileName = sprintf(
                '%s.%s',
                $do->getEntityId(),
                $do->getExtension()
            );
        } elseif ($options->getVariant() == TYPE_SVG) {
            $fileName = sprintf(
                '%s.%s',
                $do->getEntityId(),
                TYPE_SVG
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
