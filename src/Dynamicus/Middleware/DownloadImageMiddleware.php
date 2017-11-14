<?php

namespace Dynamicus\Middleware;

use Common\Entity\ImageDataObject;
use Common\Entity\ImageFile;
use Common\Exception\RuntimeException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class UploadImageMiddleware
 * @package Dinamicus\Middleware
 */
class DownloadImageMiddleware implements MiddlewareInterface
{
    const MAX_FILE_SIZE = 10485760 /* 10Mb */;

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        /* @var ImageDataObject $do */
        $do = $request->getAttribute(ImageDataObject::class);
        $queryData = $request->getParsedBody();

        /* Проверка размера загружаемого имиджа */
        if (!$this->checkFileSize($queryData['data']['links']['url'])) {
            throw new RuntimeException('The downloading file size is more than 10Mb');
        }

        /* Объект ImageFile с путями */
        $image = $this->getImageFile($do, $queryData['data']['links']['url']);
        /* Загрузка имиджа в tmp */
        $this->uploadImage($queryData['data']['links']['url'], $image->getPath());

        /* Проверка типа имиджа */
        if (!$this->validImageType($image->getPath())) {
            /* Удаление файла с неправильным типом */
            unlink($image->getPath());
            throw new RuntimeException('Filetype is not a image. It was been removed!');
        }

        /* Добавление имиджа в коллекцию имиджей */
        $do->attachImageFile($image);
        $do->getImageFiles()->rewind();

        return $delegate->process($request);
    }

    /**
     * Получение объекта ImageFile с путями к файлу
     * @param ImageDataObject $do
     * @param string          $imageUrl
     * @return ImageFile
     */
    private function getImageFile(ImageDataObject $do, $imageUrl): ImageFile
    {
        $extension = pathinfo($imageUrl, PATHINFO_EXTENSION);
        $do->setExtension($extension);

        /* Создание директорий */
        $this->createFoldersRecursive($do->getTmpDirectoryPath());

        $path = $do->getTmpDirectoryPath() . $do->getEntityId() . '.' . $extension;
        $url = $do->getRelativeDirectoryUrl() . $do->getEntityId() . '.' . $extension;

        $image = new ImageFile();
        $image->setPath($path);
        $image->setUrl($url);

        return $image;
    }

    /**
     * Загрузка файла в локальную папку
     * http://guzzle.readthedocs.io/en/latest/request-options.html#sink-option
     * @param string $fromFile
     * @param string $toFile
     */
    private function uploadImage($fromFile, $toFile)
    {
        $resource = fopen($toFile, 'w');
        $this->getGuzzleClient()->request(
            'GET',
            $fromFile,
            ['sink' => $resource]
        );
    }

    private function createFoldersRecursive($path): ?bool
    {
        $result = null;
        if (!file_exists($path)) {
            $result = mkdir($path, 0775, true);
        }

        return $result;
    }

    private function getGuzzleClient(): Client
    {
        $handler = new CurlHandler();
        $stack = HandlerStack::create($handler);
        return new Client(['handler' => $stack]);
    }

    private function checkFileSize($fileUrl): bool
    {
        $response = $this->getGuzzleClient()->head($fileUrl);
        $length = $response->getHeader('Content-Length')[0];
        return $length < self::MAX_FILE_SIZE;
    }

    private function validImageType($filePath)
    {
        $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG);
        $detectedType = exif_imagetype($filePath);
        return in_array($detectedType, $allowedTypes);
    }
}
