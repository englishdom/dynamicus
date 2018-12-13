<?php

namespace Common\Action;


use League\Flysystem\Filesystem;

abstract class AbstractAction implements ActionInterface
{
    /**
     * @param string $url
     * @return array|null
     * @throws \League\Flysystem\FileNotFoundException
     */
    protected function getFileInfo(string $url): ?array
    {
        $result = null;
        $fileSystem = new Filesystem($this->fileSystemAdapter);

        if ($fileSystem->has($url)) {
            $result = $fileSystem->getMetadata($url);
        }

        return $result;
    }
}