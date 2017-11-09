<?php

namespace Dynamicus\Middleware;

use Common\Entity\ImageDataObject;
use Common\Entity\ImageFile;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
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
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        /* @var ImageDataObject $do */
        $do = $request->getAttribute(ImageDataObject::class);
        $queryData = $request->getParsedBody();

        /* Объект ImageFile с путями */
        $image = $this->getImageFile($do, $queryData['data']['links']['url']);
        /* Загрузка имиджа в стрим */
        $this->uploadImage($queryData['data']['links']['url'], $image->getPath());

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
            $result = mkdir($path, 0777, true);
        }

        return $result;
    }

    private function getGuzzleClient(): ClientInterface
    {
        $handler = new CurlHandler();
        $stack = HandlerStack::create($handler);
        return new Client(['handler' => $stack]);
    }
}
