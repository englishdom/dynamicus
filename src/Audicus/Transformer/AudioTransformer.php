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
            'links' => null,
            'info' => null
        ];

        if ($entity->getFiles()) {
            foreach ($entity->getFiles() as $file) {
                $data['links'][] = $file->getUrl();
                $data['info'][] = $file->getMetaData();
            }
        }
        return $data;
    }
}
