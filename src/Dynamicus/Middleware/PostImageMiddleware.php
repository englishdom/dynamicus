<?php

namespace Dynamicus\Middleware;

use Common\Entity\DataObject;
use Common\Entity\File;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class PostImageMiddleware
 * @package Dynamicus\Middleware
 */
class PostImageMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        /* @var DataObject $do */
        $do = $request->getAttribute(DataObject::class);

        $image = $this->getImageFile($do);
        /* Image set to collection */
        $do->attachFile($image);
        $do->getFiles()->rewind();

        return $delegate->process($request);
    }

    protected function getImageFile(DataObject $do): File
    {
        $movedPath = $do->getTmpDirectoryPath() . $do->getEntityId() . '.' . $do->getExtension();
        /* Create tmp folder */
        if (!file_exists($do->getTmpDirectoryPath())) {
            mkdir($do->getTmpDirectoryPath(), 0755, true);
        }
        /* Move uploaded image */
        move_uploaded_file($_FILES['image']['tmp_name'], $movedPath);
        $url = $do->getRelativeDirectoryUrl() . $do->getEntityId() . '.' . $do->getExtension();

        $image = new File();
        $image->setPath($movedPath);
        $image->setUrl($url);

        return $image;
    }
}
