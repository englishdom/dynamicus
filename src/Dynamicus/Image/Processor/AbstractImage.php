<?php

namespace Dynamicus\Image\Processor;

use Common\Exception\SaveResourceException;

/**
 * Class AbstractImage
 * @package Dinamicus\Middleware
 */
abstract class AbstractImage
{
    public function getImagick(string $sourcePath): \Imagick
    {
        if (!class_exists(\Imagick::class)) {
            throw new SaveResourceException('Imagick is not installed');
        }

        return new \Imagick(realpath($sourcePath));
    }
}
