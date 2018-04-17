<?php

namespace Audicus\Entity;

/**
 * Class AudioDataObject
 * @package Audicus\Entity
 */
class AudioDataObject
{
    const TEXT_TYPE_TEXT = 'text';
    const TEXT_TYPE_SSML = 'ssml';
    
    protected $message;
    protected $voice;
    protected $language = 'en_US';
    protected $textType = 'text';

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getVoice()
    {
        return $this->voice;
    }

    /**
     * @param mixed $voice
     */
    public function setVoice($voice): void
    {
        $this->voice = $voice;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getTextType(): string
    {
        return $this->textType;
    }

    /**
     * @param string $textType
     */
    public function setTextType(string $textType): void
    {
        $this->textType = $textType;
    }
}
