<?php

namespace Dynamicus\Image\Processor;

use Common\Entity\ImageFile;
use Dynamicus\Image\Options;

/**
 * Class AutoResizeImage
 * @package Dynamicus\Image\Processor
 */
class AutoResizeImage extends AbstractImage implements ProcessorInterface
{
    public function process(ImageFile $imageFile, Options $options)
    {
        if ($options->getAutoResize()) {
            $imagick = $this->getImagick($imageFile->getPath());
            $newSizes = $this->getBiggerSizes($imagick, $options);
            /* Ресайз имиджа по большей стороне */
            $imagick->resizeImage(
                $newSizes[0],
                $newSizes[1],
                \Imagick::FILTER_LANCZOS,
                1
            );
            /* Кроп имиджа от центра */
            $newCoordinates = $this->getCoordinatesFromCenter($imagick, $options);
            $imagick->cropImage(
                $options->getAutoResize()[0],
                $options->getAutoResize()[1],
                $newCoordinates[0],
                $newCoordinates[1]
            );
            $imagick->writeImage($imageFile->getPath());
            $imagick->clear();
            $imagick->destroy();
        }
    }

    /**
     * Получение X / Y координат от центра оригинального имиджа
     * @param \Imagick $imagick
     * @param Options  $options
     * @return array
     */
    private function getCoordinatesFromCenter(\Imagick $imagick, Options $options): array
    {
        $newX = 0;
        $newY = 0;

        /* Половина ширины оригинального имиджа */
        $halfOriginalWeight = $imagick->getImageWidth() / 2;
        /* Половина высоты оригинального имиджа */
        $halfOriginalHeight = $imagick->getImageHeight() / 2;

        /* Половина ширины нового имиджа */
        $halfNewWidth = $options->getAutoResize()[0] / 2;
        /* Половина высоты нового имиджа */
        $halfNewHeight = $options->getAutoResize()[1] / 2;

        /* Если половина ширины оригинального имиджа больше половины ширины нового имиджа получение минимального смещения по X */
        if ($halfOriginalWeight > $halfNewWidth) {
            $newX = floor($halfOriginalWeight - $halfNewWidth);
        }
        /* Если половина высоты оригинального имиджа больше половины высоты нового имиджа получение минимального смещения по Y */
        if ($halfOriginalHeight > $halfNewHeight) {
            $newY = floor($halfOriginalHeight - $halfNewHeight);
        }

        return [
            $newX,
            $newY
        ];
    }

    /**
     * Получение размеров большей стороны.
     * Например если оригинальный имижд 400х200, а новые размеры 200х150 тогда вурнутся размеры 200х0
     * Иначе будет сделан ресайз имиджа как 200х100
     * @param \Imagick $imagick
     * @param Options  $options
     * @return array
     */
    private function getBiggerSizes(\Imagick $imagick, Options $options): array
    {
        $newWidth = $options->getAutoResize()[0];
        $newHeight = $options->getAutoResize()[1];

        $proportionWidth = $imagick->getImageWidth() / $options->getAutoResize()[0];
        $proportionHeight = $imagick->getImageHeight() / $options->getAutoResize()[1];
        if ($proportionWidth < $proportionHeight) {
            $newHeight = 0;
        } elseif ($proportionWidth > $proportionHeight) {
            $newWidth = 0;
        }

        return [
            $newWidth,
            $newHeight
        ];
    }
}
