<?php

namespace Dinamicus\Entity;

use Common\Entity\EntityInterface;

/**
 * @package Imagenaties\Entity
 */
class ImageDataObject implements EntityInterface
{
    private $entityName;
    private $entityId;
    private $imagesPath = [];

    public function getId(): int
    {
        return $this->getEntityId();
    }

    public function setId($identifier)
    {
        return $this->setEntityId($identifier);
    }

    /**
     * @return mixed
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * @param mixed $entityName
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
    }

    /**
     * @return mixed
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * @param mixed $entityId
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;
    }

    /**
     * @return array
     */
    public function getImagesPath(): array
    {
        return $this->imagesPath;
    }

    /**
     * @param array $imagesPath
     */
    public function setImagesPath(array $imagesPath)
    {
        $this->imagesPath = $imagesPath;
    }
}
