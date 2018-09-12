<?php

namespace Audicus\Middleware;

use Common\Entity\DataObject;
use Common\Entity\File;
use Common\Middleware\ConstantMiddlewareInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use League\Flysystem\FilesystemInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class UploadFileMiddleware
 * @package Audicus\Middleware
 */
class UploadFileMiddleware implements MiddlewareInterface, ConstantMiddlewareInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     * @return ResponseInterface
     * @throws \Common\Exception\RuntimeException
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $audioContent = $request->getAttribute(self::AUDIO_CONTENT);
        $do = $request->getAttribute(DataObject::class);
        $file = $this->attachFile($do, $request);
        /* сохранение файла если контент не пустой */
        if ($audioContent) {
            $collection = $request->getAttribute(FilesystemInterface::class);
            foreach ($collection as $fileSystem) {
                /* @var FilesystemInterface $fileSystem */
                $fileSystem->put($file->getPath(), $audioContent);
            }
        }

        return $delegate->process($request);
    }

    /**
     * @param DataObject $do
     * @param ServerRequestInterface $request
     * @return File
     * @throws \Common\Exception\RuntimeException
     */
    protected function attachFile(DataObject $do, ServerRequestInterface $request): File
    {
        $hash = $request->getAttribute(self::HASH);
        $fileName = $hash . '.' . $do->getExtension();
        $url = $do->getRelativeDirectoryUrl() . $fileName;
        $path = $do->getShardingPath() . DIRECTORY_SEPARATOR . $fileName;

        $file = new File();
        $file->setUrl($url);
        $file->setPath($path);

        $do->attachFile($file);

        return $file;
    }
}
