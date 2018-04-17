<?php

namespace Audicus\Transformer;

use Common\Entity\DataObject;
use Common\Exception\RuntimeException;
use League\Fractal\TransformerAbstract;

/**
 * Class AudioTransformer
 * @package Audicus\Transformer
 */
class AudioTransformer extends TransformerAbstract
{
    /**
     * @param DataObject $entity
     * @return array
     * @throws RuntimeException
     */
    public function transform(DataObject $entity): array
    {
        $data = [
            'id' => $entity->getId(),
        ];

        if (!$entity->getFiles()) {
            throw new RuntimeException('Audio files not found!');
        }

        if ($entity->getFiles()) {
            foreach ($entity->getFiles() as $image) {
                $data['links'][] = $image->getUrl();
            }
        }
        return $data;
    }
}
