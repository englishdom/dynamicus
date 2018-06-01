<?php

namespace Common\Factory;

use Common\Helper\BodyParams\MultipartJsonStrategy;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;

/**
 * Class BodyParseMiddlewareFactory
 * @package Common\Factory
 */
class BodyParseMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $bodyParams = new BodyParamsMiddleware();
        $bodyParams->addStrategy(new MultipartJsonStrategy());
        return $bodyParams;
    }
}
