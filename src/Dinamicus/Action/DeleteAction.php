<?php

namespace Dinamicus\Action;

use Common\Action\ActionInterface;
use Common\Entity\ImageDataObject;
use Interop\Http\ServerMiddleware\DelegateInterface;
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
        /* @var ImageDataObject $do */
        $do = $request->getAttribute(ImageDataObject::class);
        $pathForDelete = $do->getAbsoluteDirectoryPath();

        /* @TODO нужен валидатор проверки что это фолдер с миджами, чтобы не удалить лишнего */
        exec('rm -r '.$pathForDelete);

        $request = $request
            ->withAttribute(self::HTTP_CODE, Response::STATUS_CODE_204);

        return $delegate->process($request);
    }

    public function getResourceName(ImageDataObject $do): string
    {
        return 'delete';
    }
}
