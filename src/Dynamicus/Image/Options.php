<?php

namespace Dynamicus\Image;

/**
 * Class ProcessOptions
 */
class Options
{
    private $variant;
    /* Размер для ресайза рисунка от центра и кропа лишних граней */
    private $autoResize = [];
    private $size = [];
    private $crop = [];
    private $quality;

    /**
     * @return mixed
     */
    public function getVariant(): string
    {
        return $this->variant;
    }

    /**
     * @param mixed $variant
     */
    public function setVariant(string $variant)
    {
        $this->variant = $variant;
    }

    /**
     * @return mixed
     */
    public function getSize(): ?array
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize(array $size)
    {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getCrop(): ?array
    {
        return $this->crop;
    }

    /**
     * @param mixed $crop
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
}
