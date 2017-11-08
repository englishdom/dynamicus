<?php

namespace Dinamicus\Transformer;

use Common\Entity\ImageDataObject;
use Common\Entity\PathObject;
use League\Fractal\TransformerAbstract;

class ImageTransformer extends TransformerAbstract
{
    public function transform(ImageDataObject $entity)
    {
        $data = [
            'id' => $entity->getId(),
            'entity' => $entity->getEntityName(),
        ];
        /* @var PathObject $image */
        foreach ($entity->getImagesPath() as $image) {
            $data['images'][] = $this->getImage($image);
        }
        return $data;
    }

    private function getImage(PathObject $image)
    {
        return $image->getPath();
    }
}
