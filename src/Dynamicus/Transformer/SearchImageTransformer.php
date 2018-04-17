<?php

namespace Dynamicus\Transformer;

use Common\Entity\DataObject;
use League\Fractal\TransformerAbstract;

class SearchImageTransformer extends TransformerAbstract
{
    public function transform(DataObject $entity): array
    {
        $data = [
            'id' => null
        ];

        if ($entity->getFiles()) {
            foreach ($entity->getFiles() as $image) {
                $data['links'][] = $image->getUrl();
            }
        }
        return $data;
    }
}
