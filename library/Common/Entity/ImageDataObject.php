<?php

namespace Common\Entity;

use Common\Exception\RuntimeException;

/**
 * @package Imagenaties\Entity
 */
class ImageDataObject implements EntityInterface
{
    /**
     * @var string
     */
    private $entityName;

    /**
     * @var int
     */
    private $entityId;

    /**
     * Путь который был построен при шардировании
     * @var string
     */
    private $shardingPath;

    /**
     * Абсолютный путь к директории имиджей
     * @var string
     */
    private $tmpDirectoryPath;

    /**
     * Относительный URL к директории имиджей
     * @var string
     */
    private $relativeDirectoryUrl;

    /**
     * Расширение оригинального файла
     * @var string
     */
    private $extension;

    /**
     * @var \SplObjectStorage
     */
    private $imageFile;

    public function getId(): ?int
    {
        return $this->getEntityId();
    }

    public function setId(int $identifier)
    {
        return $this->setEntityId($identifier);
    }

    /**
     * @return string
     */
    public function getEntityName(): ?string
    {
        return $this->entityName;
    }

    /**
     * @param string $entityName
     */
    public function setEntityName(string $entityName)
    {
        $this->entityName = $entityName;
    }

    /**
     * @return mixed
     */
    public function getEntityId(): ?int
    {
        return $this->entityId;
    }

    /**
     * @param mixed $entityId
     */
    public function setEntityId(int $entityId)
    {
        $this->entityId = $entityId;
    }

    /**
     * @return null|string
     */
    public function getShardingPath(): ?string
    {
        return $this->shardingPath;
    }

    /**
     * @param string $shardingPath
     */
    public function setShardingPath(string $shardingPath)
    {
        $this->shardingPath = $shardingPath;
    }

    /**
     * @return string
     */
    public function getTmpDirectoryPath(): string
    {
        return $this->tmpDirectoryPath;
    }

    /**
     * @param string $tmpDirectoryPath
     */
    public function setTmpDirectoryPath(string $tmpDirectoryPath)
    {
        $this->tmpDirectoryPath = $tmpDirectoryPath;
    }

    /**
     * @return string
     */
    public function getRelativeDirectoryUrl(): string
    {
        return $this->relativeDirectoryUrl;
    }

    /**
     * @param string $relativeDirectoryUrl
     */
    public function setRelativeDirectoryUrl(string $relativeDirectoryUrl)
    {
        $this->relativeDirectoryUrl = $relativeDirectoryUrl;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     */
    public function setExtension(string $extension)
    {
        $this->extension = $extension;
    }

    /**
     * @return \SplObjectStorage|ImageFile[]
     */
    public function getImageFiles(): ?\SplObjectStorage
    {
        return $this->imageFile;
    }

    /**
     * @param \SplObjectStorage $imageFile
     */
    public function setImageFiles(\SplObjectStorage $imageFile)
    {
        $this->imageFile = $imageFile;
    }

    /**
     * Fluent Interface
     * @param ImageFile $image
     * @param bool      $replace
     * @return $this
     * @throws RuntimeException
     */
    public function attachImageFile(ImageFile $image, $replace = false)
    {
        if (!$this->imageFile instanceof \SplObjectStorage) {
            $this->setImageFiles(new \SplObjectStorage());
        }

        if ($this->imageFile->offsetExists($image) && !$replace) {
            throw new RuntimeException('The PathObject is exist in a storage!');
        }
        $this->imageFile->attach($image);
        return $this;
    }
}
