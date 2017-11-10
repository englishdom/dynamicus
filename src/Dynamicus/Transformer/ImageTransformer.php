<?php

namespace Dynamicus\Transformer;

use Common\Entity\ImageDataObject;
use Common\Exception\RuntimeException;
use League\Fractal\TransformerAbstract;

class ImageTransformer extends TransformerAbstract
{
    public function transform(ImageDataObject $entity): array
    {
        $data = [
            'id' => $entity->getId(),
        ];

        /* @TODO бросать исключение или возвращать пустой links ? */
        if (!$entity->getImageFiles()) {
            throw new RuntimeException('Images not found!');
        }

        if ($entity->getImageFiles()) {
            foreach ($entity->getImageFiles() as $image) {
                $fileInfo = $this->getFileInfo($image->getUrl());
                if (!$fileInfo['size']) {
                    $data['links'][$fileInfo['variant']] = $image->getUrl();
                } else {
                    $data['links'][$fileInfo['variant']][$fileInfo['size']] = $image->getUrl();
                }
            }
        }
        return $data;
    }

    /**
     * Получение варианта и размера из имени файла
     * @param string $path
     * @return array
     */
    private function getFileInfo($path): array
    {
        $pathInfo = pathinfo($path);
        $fileInfo = explode('_', $pathInfo['filename']);
        $filePartsCount = count($fileInfo);

        if ($filePartsCount == 3) { /* В имени файла есть id, variant, size */
            $variant = $fileInfo[1];
            $size = $fileInfo[2];
        } elseif ($filePartsCount == 2) { /* В имени файла есть id, size */
            $variant = 'default';
            $size = $fileInfo[1];
        } else { /* В имени файла есть id. Значит это ориганал */
            $variant = 'original';
            $size = null;
        }

        return [
            'variant' => $variant,
            'size' => $size,
        ];
    }
}
