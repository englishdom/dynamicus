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

        /* Объект ImageFile с путями */
        $image = $this->getImageFile($do, $queryData['data']['links']['url']);
        /* Загрузка имиджа в tmp */
        $response = $this->uploadImage($queryData['data']['links']['url']);
        $this->allowDownloadingSize($response, $image->getPath());

        /* Проверка типа имиджа */
        if (!$this->validImageType($image->getPath())) {
            /* Удаление файла с неправильным типом */
            unlink($image->getPath());
            throw new RuntimeException('Filetype is not an image. It has been removed!');
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
        $extension = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
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
     * @return ResponseInterface
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
     * Открытие ресурса на запись
     * Чтение из потока по 1Кб
     * Запись в ресурс или Exception с удалением файла
     * Закрытие ресурса
     * @param ResponseInterface $response
     * @param                   $toFile
     * @throws \Exception
     */
    private function allowDownloadingSize(ResponseInterface $response, $toFile)
    {
        $resource = fopen($toFile, 'w');
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
     * Проверка типа файла из разрешенных
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
