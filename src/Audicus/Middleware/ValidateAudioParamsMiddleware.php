<?php

namespace Audicus\Middleware;

use Audicus\Entity\AudioDataObject;
use Common\Exception\InvalidParameterException;
use Common\Exception\WrongMessageLengthException;
use Common\Middleware\ConstantMiddlewareInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class PrepareAudioDataObjectMiddleware
 * @package Audicus\Middleware
 */
class ValidateAudioParamsMiddleware implements MiddlewareInterface, ConstantMiddlewareInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     * @return ResponseInterface
     * @throws InvalidParameterException
     * @throws WrongMessageLengthException
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $queryData = $request->getParsedBody();
        $audio = new AudioDataObject();
        if (isset($queryData['data']['message'])) {
            $audio->setMessage($queryData['data']['message']);
        } else {
            throw new InvalidParameterException('Does not exist parameter `data.message`!');
        }

        if (mb_strlen($audio->getMessage()) > self::AUDIO_MESSAGE_LENGTH) {
            throw new WrongMessageLengthException('Audio message length more '.self::AUDIO_MESSAGE_LENGTH);
        }

        if (isset($queryData['data']['voice'])) {
            $audio->setVoice($queryData['data']['voice']);
        }

        if (isset($queryData['data']['lang'])) {
            $audio->setLanguage($queryData['data']['lang']);
        }

        if (strstr($audio->getMessage(), '<speak>')) {
            $audio->setTextType(AudioDataObject::TEXT_TYPE_SSML);
        }

        $request = $request->withAttribute(AudioDataObject::class, $audio);
        return $delegate->process($request);
    }

}
