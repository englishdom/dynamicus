<?php

namespace Dynamicus\Image;

/**
 * Class ProcessOptions
 */
class Options
{
    private $variant;
    private $autoResize = [];
    private $size = [];
    private $crop = [];
    private $quality;
    private $fileNameSizes = [];

    /**
     * @return string
     */
    public function getVariant(): string
    {
        return $this->variant;
    }

    /**
     * @param string $variant
     */
    public function setVariant(string $variant)
    {
        $this->variant = $variant;
    }

    /**
     * @return array
     */
    public function getSize(): ?array
    {
        return $this->size;
    }

    /**
     * Массив из 2х значений.
     * 1е значение - ширина, 2е значение - высота
     * @param array $size
     */
    public function setSize(array $size)
    {
        $this->size = $size;
    }

    /**
     * @return array
     */
    public function getCrop(): ?array
    {
        return $this->crop;
    }

    /**
     * Массив из 4х значений.
     * 1,2 значения - координаты верхней левой точки изображения
     * 3,4 значения - координаты нижней правой точки изображения
     * @param array $crop
     */
    public function setCrop(array $crop)
    {
        $this->crop = $crop;
    }

    /**
     * @return array
     */
    public function getAutoResize(): ?array
    {
        return $this->autoResize;
    }

    /**
     * Массив из 2х значений
     * 1е значение - ширина
     * 2е значение - высота
     * Необходимо для автоматического ресайза и кропа.
     * @see \Dynamicus\Image\Processor\AutoResizeImage
     * @param array $autoResize
     */
    public function setAutoResize(array $autoResize)
    {
        $this->autoResize = $autoResize;
    }

    /**
     * @return mixed
     */
    public function getQuality(): ?int
    {
        return $this->quality;
    }

    /**
     * @param mixed $quality
     */
    public function setQuality(int $quality)
    {
        $this->quality = $quality;
    }

    /**
     * @return array
     */
    public function getFileNameSizes(): array
    {
        return $this->fileNameSizes;
    }

    /**
     * @param array $fileNameSizes
     */
    public function setFileNameSizes(array $fileNameSizes)
    {
        $this->fileNameSizes = $fileNameSizes;
    }
}
