<?php

namespace Audicus\Middleware;

use Audicus\Entity\AudioDataObject;
use Common\Container\ConfigInterface;
use Common\Middleware\ConstantMiddlewareInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class GenerateAudioMiddleware
 * @package Audicus\Middleware
 */
class GenerateAudioMiddleware implements MiddlewareInterface, ConstantMiddlewareInterface
{
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * GenerateAudioMiddleware constructor.
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * Если не существует аудио файла запрос генерации аудио
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        if ($request->getAttribute(self::FILE_EXISTS) === false) {
            $audioDataObject = $request->getAttribute(AudioDataObject::class);
            $request = $request->withAttribute(
                self::AUDIO_CONTENT,
                $this->getAudio($audioDataObject)
            );
        }

        return $delegate->process($request);
    }

    protected function getAudio(AudioDataObject $dto)
    {
        $adapter = new \AudioManager\Adapter\Polly();
        $adapter->getOptions()->initialize()
            ->setVersion('latest')
            ->setRegion('us-west-2')
            ->setCredentials()
                ->setKey($this->config->get('polly.key'))
                ->setSecret($this->config->get('polly.secret'));

        if ($dto->getVoice()) {
            $adapter->getOptions()->setVoiceId($dto->getVoice());
        }

        if ($dto->getLanguage()) {
            $adapter->getOptions()->setLanguage($dto->getLanguage());
        }

        if ($dto->getTextType()) {
            $adapter->getOptions()->setTextType($dto->getTextType());
        }

        $manager = new \AudioManager\Manager($adapter);
        $audioContent = $manager->read($dto->getMessage());

        return $audioContent;
    }
}
