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
    private $absoluteDirectoryPath;

    /**
     * Относительный URL к директории имиджей
     * @var string
     */
    private $relativeDirectoryUrl;

    /**
     * @var string
     */
    private $imageExtension;

    /**
     * @var \SplObjectStorage
     */
    private $imagesPath;

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
    public function getAbsoluteDirectoryPath(): string
    {
        return $this->absoluteDirectoryPath;
    }

    /**
     * @param string $absoluteDirectoryPath
     */
    public function setAbsoluteDirectoryPath(string $absoluteDirectoryPath)
    {
        $this->absoluteDirectoryPath = $absoluteDirectoryPath;
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
    public function getImageExtension(): string
    {
        return $this->imageExtension;
    }

    /**
     * @param string $imageExtension
     */
    public function setImageExtension(string $imageExtension)
    {
        $this->imageExtension = $imageExtension;
    }

    /**
     * @return \SplObjectStorage
     */
    public function getImagesPath(): ?\SplObjectStorage
    {
        return $this->imagesPath;
    }

    /**
     * @param \SplObjectStorage $imagesPath
     */
    public function setImagesPath(\SplObjectStorage $imagesPath)
    {
        $this->imagesPath = $imagesPath;
    }

    /**
     * Fluent Interface
     * @param PathObject $object
     * @param bool       $replace
     * @return $this
     * @throws RuntimeException
     */
    public function attachImagePath(PathObject $object, $replace = false)
    {
        if (!$this->imagesPath instanceof \SplObjectStorage) {
            $this->setImagesPath(new \SplObjectStorage());
        }

        if ($this->imagesPath->offsetExists($object) && !$replace) {
            throw new RuntimeException('The PathObject is exist in a storage!');
        }
        $this->imagesPath->attach($object);
        return $this;
    }
}
