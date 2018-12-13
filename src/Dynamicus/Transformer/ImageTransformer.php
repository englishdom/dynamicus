<?php

namespace Dynamicus\Transformer;

use Common\Entity\DataObject;
use Common\Exception\RuntimeException;
use League\Fractal\TransformerAbstract;

class ImageTransformer extends TransformerAbstract
{
    public function transform(DataObject $entity): array
    {
        $data = [
            'id' => $entity->getId(),
            'links' => null,
            'info' => null
        ];

        if (!$entity->getFiles()) {
            throw new RuntimeException('Images not found!');
        }

        if ($entity->getFiles()) {
            foreach ($entity->getFiles() as $image) {
                $fileInfo = $this->getFileInfo($image->getUrl());
                if (!$fileInfo['size']) {
                    $data['links'][$fileInfo['variant']] = $image->getUrl();
                    $data['info'][$fileInfo['variant']] = $image->getMetaData();
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

        /* если тип SVG */
        if ($pathInfo['extension'] == TYPE_SVG) {
            $variant = TYPE_SVG;
            $size = null;
        } elseif ($filePartsCount == 3) { /* В имени файла есть id, variant, size */
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
