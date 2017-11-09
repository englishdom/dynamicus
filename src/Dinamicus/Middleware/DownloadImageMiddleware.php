<?php

namespace Dinamicus\Middleware;

use Common\Entity\ImageDataObject;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use League\Flysystem\FilesystemInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Class UploadImageMiddleware
 * @package Dinamicus\Middleware
 */
class DownloadImageMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $do = $request->getAttribute(ImageDataObject::class);
        $filesystem = $request->getAttribute(FilesystemInterface::class);
        $queryData = $request->getParsedBody();

        /* Получение рассширения файла */
        $this->setExtension($do, $queryData);
        /* Загрузка имиджа в темповый стрим */
        $stream = $this->uploadImage($queryData['data']['links']['url']);
        /* Запись темпового стрима в файловую систему */
        $this->writeImage($do, $filesystem, $stream);

        return $delegate->process($request);
    }

    private function setExtension(ImageDataObject $do, $queryData)
    {
        $extension = pathinfo($queryData['data']['links']['url'], PATHINFO_EXTENSION);
        $do->setImageExtension($extension);
    }

    private function writeImage(ImageDataObject $do, FilesystemInterface $filesystem, $stream)
    {
        $filePath = $do->getShardingPath() . DIRECTORY_SEPARATOR
            . $do->getEntityId() . '.' . $do->getImageExtension();
        $filesystem->write($filePath, $stream);
    }

    /**
     * Желательно переделать на sink
     * http://guzzle.readthedocs.io/en/latest/request-options.html#sink-option
     * @param string $link
     * @return \Psr\Http\Message\StreamInterface
     */
    private function uploadImage($link): StreamInterface
    {
        $response = $this->getGuzzleClient()->request(
            'GET',
            $link
        );
        return $response->getBody();
    }

    private function getGuzzleClient(): ClientInterface
    {
        $handler = new CurlHandler();
        $stack = HandlerStack::create($handler);
        return new Client(['handler' => $stack]);
    }
}
