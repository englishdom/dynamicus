<?php

namespace Dynamicus\Middleware;

use Common\Entity\DataObject;
use Common\Entity\File;
use Common\Exception\RuntimeException;
use Common\Exception\UnsupportedMediaException;
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
    const MAX_FILE_SIZE = 5242880 /* 5Mb */;

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     * @return ResponseInterface
     * @throws RuntimeException
     * @throws UnsupportedMediaException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        /* @var DataObject $do */
        $do = $request->getAttribute(DataObject::class);
        $queryData = $request->getParsedBody();

        /* Object ImageFile with paths */
        $image = $this->getImageFile($do, $queryData['data']['links']['url']);
        /* Image uploading to tmp */
        $response = $this->uploadImage($queryData['data']['links']['url']);
        $this->allowDownloadingSize($response, $image->getPath());

        /* Image type check */
        if (!$this->validImageType($image->getPath())) {
            /* Image remove with wrong type */
            unlink($image->getPath());
            throw new RuntimeException('File type is not an image. It has been removed!');
        }

        /* Image set to collection */
        $do->attachFile($image);
        $do->getFiles()->rewind();

        return $delegate->process($request);
    }

    /**
     * Object ImageFile get with paths to files
     * @param DataObject $do
     * @param string     $imageUrl
     * @return File
     * @throws UnsupportedMediaException
     */
    private function getImageFile(DataObject $do, $imageUrl): File
    {
        $extension = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
        if (!in_array($extension, ['jpeg', 'jpg'])) {
            throw new UnsupportedMediaException('The image unsupported with type: '.$extension);
        }

        /* Directory creating */
        $this->createFoldersRecursive($do->getTmpDirectoryPath());

        $path = $do->getTmpDirectoryPath() . $do->getEntityId() . '.' . $do->getExtension();
        $url = $do->getRelativeDirectoryUrl() . $do->getEntityId() . '.' . $do->getExtension();

        $image = new File();
        $image->setPath($path);
        $image->setUrl($url);

        return $image;
    }

    /**
     * File uploading to folder
     * http://guzzle.readthedocs.io/en/latest/request-options.html#sink-option
     * @param string $fromFile
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function uploadImage($fromFile): ResponseInterface
    {
        $response = $this->getGuzzleClient()->request(
            'GET',
            $fromFile,
            ['stream' => true]
        );
        return $response;
    }

    /**
     * Resource open
     * 1Kb reading from resource
     * Resource write with remove file
     * Resource close
     * @param ResponseInterface $response
     * @param                   $toFile
     * @throws RuntimeException
     */
    private function allowDownloadingSize(ResponseInterface $response, $toFile)
    {
        $resource = fopen($toFile, 'c');
        $body = $response->getBody();

        $bytesRead = 0;
        $dataRead = "";
        while (!$body->eof()) {
            $data = $body->read(1024);
            $dataRead .= $data;
            $bytesRead += strlen($data);
            if($bytesRead >= self::MAX_FILE_SIZE) {
                fclose($resource);
                unlink($toFile);
                throw new RuntimeException('The image\'s file size more 5Mb');
            }
            fwrite($resource, $data);
        }
        fclose($resource);
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

    /**
     * Image type checking
     * @param $filePath
     * @return bool
     */
    private function validImageType($filePath): bool
    {
        $allowedTypes = array('image/jpeg', 'image/jpg', 'image/png', 'image/svg');
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $detectedType = finfo_file($finfo, $filePath);
        return in_array($detectedType, $allowedTypes);
    }
}
