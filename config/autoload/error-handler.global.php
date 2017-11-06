<?php

use Zend\Http;
use Common\Exception;
use Common\Factory;
use Zend\Expressive\Middleware\ErrorResponseGenerator;
use Zend\Stratigility\Middleware\ErrorHandler;

return [
    'dependencies' => [
        'factories'  => [
            ErrorResponseGenerator::class => Factory\ErrorResponseGeneratorFactory::class,
        ],
        'delegators' => [
            ErrorHandler::class => [
                Factory\LoggingErrorListenerDelegatorFactory::class,
            ]
        ]
    ],
    'error-handler' => [
        'response-code' => [
            Exception\BadRequestException::class => Http\Response::STATUS_CODE_400,
            Exception\NotFoundException::class => Http\Response::STATUS_CODE_404,
            Exception\ConflictException::class => Http\Response::STATUS_CODE_409,
            Exception\UnauthorizedException::class => Http\Response::STATUS_CODE_401,
            Exception\UnsupportedMediaException::class => Http\Response::STATUS_CODE_415,
            Exception\NotAcceptableException::class => Http\Response::STATUS_CODE_406,
        ],
        'write-to-log' => [
            /* ParseError is thrown when an error occurs while parsing PHP code, such as when eval() is called. */
            ParseError::class,
            /* There are three scenarios where a TypeError may be thrown. */
            TypeError::class,
            DivisionByZeroError::class,
            /* Error is the base class for all internal PHP errors. */
            Error::class,
            /* An Error Exception. */
            ErrorException::class,
            \Exception::class,
            \Zend\ServiceManager\Exception\ServiceNotCreatedException::class,
        ],
    ]
];
