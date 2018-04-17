<?php

namespace Dynamicus\Image;

use Common\Container\ConfigInterface;
use Common\Entity\DataObject;
use Common\Entity\File;
use Common\Exception\NotFoundException;
use Dynamicus\Image\Processor\AutoResizeImage;
use Dynamicus\Image\Processor\CleanImage;
use Dynamicus\Image\Processor\CompressImage;
use Dynamicus\Image\Processor\CropImage;
use Dynamicus\Image\Processor\ProcessorInterface;
use Dynamicus\Image\Processor\ResizeImage;
use Dynamicus\Image\Transformer\Plugin\ParsingConfigArray;
use Dynamicus\Image\Transformer\Plugin\ParsingPostArray;
use Dynamicus\Image\Transformer\Transformer;

/**
 * Менеджер который знает как обрабатывать типы имиджей
 * @package Dynamicus\Image
 */
class ImageCreator implements ImageCreatorInterface
{
    use ImageCreatorTrait;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var array
     */
    private $processMap = [
        CleanImage::class,
        CompressImage::class,
        CropImage::class,
        ResizeImage::class,
        AutoResizeImage::class,
    ];

    /**
     * ImageManager constructor.
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * Преобразования имиджей из оригинального файла и добавление новых имиджей в коллекцию
     * @TODO: добавить возможность нарезки других форматов картинок
     * @param DataObject $do
     * @param array      $request
     * @return bool
     * @throws NotFoundException
     * @throws \Common\Exception\RuntimeException
     */
    public function process(DataObject $do, array $request)
    {
        $originalImageFile = $do->getFiles()->current();

        /* @var Options $options */
        foreach ($this->getOptionsContainer($do, $request) as $options) {
            $newImageFile = $this->createImageFile($options, $do);
            /* Копирование оригинального имиджа в новый файл */
            copy($originalImageFile->getPath(), $newImageFile->getPath());
            /* Выполнение процессоров для нового имиджа */
            $this->callProcessors($newImageFile, $options);
            $do->attachFile($newImageFile);
        }

        return true;
    }

    /**
     * Получение контейнера с опциами ресайза и кропа
     * @param DataObject $do
     * @param array      $request
     * @return \SplObjectStorage|Options[]
     * @throws NotFoundException
     */
    private function getOptionsContainer(DataObject $do, array $request): \SplObjectStorage
    {
        $configSizes = $this->config->get('images.'.$do->getEntityName());
        /* Ошибка если в конфиге не найдены размеры для указаной entity */
        if (!$configSizes) {
            throw new NotFoundException('The image\'s config does not exist for entity: '.$do->getEntityName());
        }

        if (isset($request['data']['resize']) && !empty($request['data']['resize'])) {
            $plugin = new ParsingPostArray($configSizes);
            $transformationParams = $request['data']['resize'];
        } else {
            $plugin = new ParsingConfigArray();
            $transformationParams = $configSizes;
            /* Выбрать только указанный namespace */
            if ($do->getNamespace() && isset($transformationParams[$do->getNamespace()])) {
                $transformationParams = [
                    $do->getNamespace() => $transformationParams[$do->getNamespace()]
                ];
            }
        }
        $transformer = new Transformer();
        $transformer->setPlugin($plugin);

        return $transformer->transform($do, $transformationParams);
    }

    /**
     * Генерации имени файла и его пути из опций
     * @param Options    $options
     * @param DataObject $do
     * @return File
     */
    private function createImageFile(Options $options, DataObject $do): File
    {
        $imageFile = new File();
        $fileName = $this->makeFileName($do, $options);
        $imageFile->setPath($do->getTmpDirectoryPath() . $fileName);
        $imageFile->setUrl($do->getRelativeDirectoryUrl() . $fileName);

        return $imageFile;
    }

    private function callProcessors(File $newImage, Options $options)
    {
        foreach ($this->processMap as $processorClass) {
            /* @var ProcessorInterface $processor */
            $processor = new $processorClass();
            $processor->process($newImage, $options);
        }
    }
}
