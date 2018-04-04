<?php

namespace Dynamicus\Middleware;

use Common\Entity\ImageDataObject;
use Common\Entity\ImageFile;
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
    const MAX_FILE_SIZE = 10485760 /* 10Mb */;

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
        /* @var ImageDataObject $do */
        $do = $request->getAttribute(ImageDataObject::class);
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
            throw new RuntimeException('Filetype is not an image. It has been removed!');
        }

        /* Image set to collection */
        $do->attachImageFile($image);
        $do->getImageFiles()->rewind();

        return $delegate->process($request);
    }

    /**
     * Object ImageFile get with paths to files
     * @param ImageDataObject $do
     * @param string          $imageUrl
     * @return ImageFile
     * @throws UnsupportedMediaException
     */
    private function getImageFile(ImageDataObject $do, $imageUrl): ImageFile
    {
        $extension = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
        if (!in_array($extension, ['jpeg', 'jpg'])) {
            throw new UnsupportedMediaException('The image unsupported with type: '.$extension);
        }
        $do->setExtension(TYPE_JPG);

        /* Directory creating */
        $this->createFoldersRecursive($do->getTmpDirectoryPath());

        $path = $do->getTmpDirectoryPath() . $do->getEntityId() . '.' . TYPE_JPG;
        $url = $do->getRelativeDirectoryUrl() . $do->getEntityId() . '.' . TYPE_JPG;

        $image = new ImageFile();
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
                throw new RuntimeException('The image\'s file size more 10Mb');
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
