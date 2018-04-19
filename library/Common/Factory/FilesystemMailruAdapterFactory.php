<?php

namespace Common\Factory;

use Common\Container\ConfigInterface;
use League\Flysystem\AdapterInterface;
use Psr\Container\ContainerInterface;
use Aws\S3\S3Client;
use \League\Flysystem\AwsS3v3\AwsS3Adapter;

/**
 * Class FilesystemMailruAdapterFactory
 * @package Common\Factory
 */
class FilesystemMailruAdapterFactory
{
    public function __invoke(ContainerInterface $container) : AdapterInterface
    {
        $config = $container->get(ConfigInterface::class);

        // настраиваем клиента
        $client = new S3Client([
            'credentials' => [
                'key'    => $config->get('filesystem.s3-compatible.key'),
                'secret' => $config->get('filesystem.s3-compatible.secret')
            ],
            'endpoint' => $config->get('filesystem.s3-compatible.endpoint'),
            'region'   => $config->get('filesystem.s3-compatible.region'),
            'version'  => $config->get('filesystem.s3-compatible.version'),
        ]);
        // возвращаем адаптер
        return new AwsS3Adapter($client, $config->get('filesystem.s3-compatible.bucket'));
    }
}
