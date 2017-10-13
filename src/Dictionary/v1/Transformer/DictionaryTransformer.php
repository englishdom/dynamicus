<?php

namespace Dictionary\Transformer;

use Dictionary\Entity\Dictionary;
use League\Fractal\TransformerAbstract;

class DictionaryTransformer extends TransformerAbstract
{
    public function transform(Dictionary $entity)
    {
        return [
            'id' => $entity->getId(),
            'text' => $entity->getText(),
            'transcription' => $entity->getTranscription(),
            'example' => $entity->getExample()
        ];
    }
}
