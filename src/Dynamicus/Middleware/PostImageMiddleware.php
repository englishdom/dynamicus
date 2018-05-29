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
        $do->setExtension(TYPE_JPG);

        $path = $_FILES['image']['tmp_name'];
        $url = $do->getRelativeDirectoryUrl() . $do->getEntityId() . '.' . TYPE_JPG;

        $image = new File();
        $image->setPath($path);
        $image->setUrl($url);

        return $image;
    }
}
