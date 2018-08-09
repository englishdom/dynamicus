<?php

namespace Common\Entity;

use Common\Exception\RuntimeException;

/**
 * @package Imagenaties\Entity
 */
class DataObject implements EntityInterface
{
    /**
     * @var string
     */
    private $entityName;

    private $namespace;

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
     * File name for original image
     * !!! ONLY for ORIGINAL IMAGE
     * @var string
     */
    private $fileName;

    /**
     * @var \SplObjectStorage
     */
    private $files;

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
    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    /**
     * @param mixed $namespace
     */
    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;
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
     * @return string
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     */
    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }

    /**
     * @return \SplObjectStorage|File[]
     */
    public function getFiles(): ?\SplObjectStorage
    {
        return $this->files;
    }

    /**
     * @param \SplObjectStorage $imageFile
     */
    public function setFiles(\SplObjectStorage $imageFile)
    {
        $this->files = $imageFile;
    }

    /**
     * Fluent Interface
     * @param File $file
     * @param bool $replace
     * @return $this
     * @throws RuntimeException
     */
    public function attachFile(File $file, $replace = false)
    {
        if (!$this->files instanceof \SplObjectStorage) {
            $this->setFiles(new \SplObjectStorage());
        }

        if ($this->files->offsetExists($file) && !$replace) {
            throw new RuntimeException('The PathObject is exist in a storage!');
        }
        $this->files->attach($file);
        return $this;
    }
}
