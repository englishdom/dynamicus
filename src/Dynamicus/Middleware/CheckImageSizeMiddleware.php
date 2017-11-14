<?php

namespace Dynamicus\Middleware;

use Common\Container\ConfigInterface;
use Common\Entity\ImageDataObject;
use Common\Exception\WrongImageSizeException;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class CheckImageSizesMiddleware
 * @package Dynamicus\Middleware
 */
class CheckImageSizeMiddleware implements MiddlewareInterface
{
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * CheckImageSizeMiddleware constructor.
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        /* @var ImageDataObject $do */
        $do = $request->getAttribute(ImageDataObject::class);
        $queryData = $request->getParsedBody();

        if (isset($queryData['data']['resize'])) {
            foreach ($queryData['data']['resize'] as $sizes) {
                if (!$this->checkSizeWithConfig($do->getEntityName(), $sizes['size'])) {
                    throw new WrongImageSizeException(
                        'Wrong image size `'.$sizes['size'].'` for entity `' . $do->getEntityName() .'`'
                    );
                }
            }
        }

        return $delegate->process($request);
    }

    /**
     * @param string $entityName
     * @param string $size 50x50
     * @return bool
     */
    private function checkSizeWithConfig(string $entityName, $size): bool
    {
        $fromConfig = $this->config->get('images.'.$entityName);

        $result = false;
        foreach ($fromConfig as $variant) {
            foreach ($variant as $options) {
                $sizeFromConfig = $options['width'] . 'x' . $options['height'];
                if ($sizeFromConfig == $size) {
                    $result = true;
                    break 2;
                }
            }
        }

        return $result;
    }
}
