<?php

namespace Dynamicus\Middleware;

use Common\Entity\ImageDataObject;
use Common\Entity\ImageFile;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use League\Flysystem\FilesystemInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class WriteImagesMiddleware
 * @package Dinamicus\Middleware
 */
class WriteImagesMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        /* @var ImageDataObject $do */
        $do = $request->getAttribute(ImageDataObject::class);
        /* @var FilesystemInterface $filesystem */
        $filesystem = $request->getAttribute(FilesystemInterface::class);

        /* @var ImageFile $imageFile */
        foreach ($do->getImageFiles() as $imageFile) {
            $this->moveImage(
                $filesystem,
                $imageFile->getPath(),
                $this->createNewPath($do->getShardingPath(), $imageFile->getPath())
            );
        }

        return $delegate->process($request);
    }

    /**
     * Создание нового пути для имиджа
     * @param $shardingPath
     * @param $imagePath
     * @return string
     */
    private function createNewPath($shardingPath, $imagePath): string
    {
        $baseName = pathinfo($imagePath, PATHINFO_BASENAME);
        return $shardingPath . DIRECTORY_SEPARATOR . $baseName;
    }

    /**
     * Копирование имиджа и удаление темпового
     * @param FilesystemInterface $filesystem
     * @param string              $tmpFilePath
     * @param string              $newFilePath
     * @return bool
     */
    private function moveImage(FilesystemInterface $filesystem, string $tmpFilePath, string $newFilePath): bool
    {
        $resource = fopen($tmpFilePath, 'r');
        if ($filesystem->has($tmpFilePath)) {
            $result = $filesystem->writeStream($newFilePath, $resource);
        } else {
            $result = $filesystem->putStream($newFilePath, $resource);
        }
        fclose($resource);
        if ($result) {
            unlink($tmpFilePath);
        }
        return $result;
    }
}
