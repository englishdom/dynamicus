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
     * @var string
     */
    private $relativePath;

    /**
     * @var string
     */
    private $absolutePath;

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
    public function getRelativePath(): ?string
    {
        return $this->relativePath;
    }

    /**
     * @param string $relativePath
     */
    public function setRelativePath(string $relativePath)
    {
        $this->relativePath = $relativePath;
    }

    /**
     * @return string
     */
    public function getAbsolutePath(): ?string
    {
        return $this->absolutePath;
    }

    /**
     * @param string $absolutePath
     */
    public function setAbsolutePath(string $absolutePath)
    {
        $this->absolutePath = $absolutePath;
    }

    /**
     * @return \SplObjectStorage
     */
    public function getImagesPath(): \SplObjectStorage
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
