<?php

namespace Common\Entity;

/**
 * Class PathObject
 * @package Common\Entity
 */
class File
{
    protected $path;
    protected $url;
    protected $metaData;

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return array|null
     */
    public function getMetaData(): ?array
    {
        return $this->metaData;
    }

    /**
     * @param array|null $metaData
     */
    public function setMetaData(?array $metaData)
    {
        $this->metaData = $metaData;
    }
}
