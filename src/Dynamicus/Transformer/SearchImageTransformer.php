<?php

namespace Dynamicus\Transformer;

use Common\Entity\ImageDataObject;
use League\Fractal\TransformerAbstract;

class SearchImageTransformer extends TransformerAbstract
{
    public function transform(ImageDataObject $entity): array
    {
        $data = [
            'id' => null
        ];

        if ($entity->getImageFiles()) {
            foreach ($entity->getImageFiles() as $image) {
                $data['links'][] = $image->getUrl();
            }
        }
        return $data;
    }
}
