<?php

namespace Dictionary\Entity;

use Common\Entity\EntityInterface;

class Dictionary implements EntityInterface
{
    protected $identifier;
    protected $text;
    protected $transcription;
    protected $example;

    public function getId(): int
    {
        return $this->identifier;
    }

    public function setId($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    public function getTranscription()
    {
        return $this->transcription;
    }

    public function setTranscription($transcription)
    {
        $this->transcription = $transcription;
    }

    public function getExample()
    {
        return $this->example;
    }

    public function setExample($example)
    {
        $this->example = $example;
    }
}
