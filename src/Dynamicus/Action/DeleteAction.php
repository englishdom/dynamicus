<?php

namespace Dynamicus\Action;

use Common\Action\ActionInterface;
use Common\Entity\DataObject;
use Interop\Http\ServerMiddleware\DelegateInterface;
use League\Flysystem\FilesystemInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Http\Response;

/**
 * Class ActionDelete
 * @package Dinamicus\Action
 */
class DeleteAction implements ActionInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        /* @var FilesystemInterface $filesystem */
        $filesystem = $request->getAttribute(FilesystemInterface::class);
        /* @var DataObject $do */
        $do = $request->getAttribute(DataObject::class);

        /* удаляется только указанная директория и все вложения */
        $filesystem->deleteDir($do->getShardingPath());

        $request = $request
            ->withAttribute(self::HTTP_CODE, Response::STATUS_CODE_204);

        return $delegate->process($request);
    }
}
