<?php

return [
    // для поиска картинок в гугл
    'google-api' => [
        'key' => env('GOOGLE_API_KEY'),
        'cx' => env('GOOGLE_API_CX'),
    ],
    // для сохранение файлов на s3 совместимое хранилище
    'filesystem' => [
        's3-compatible' => [
            'bucket' =>  env('S3_COMPATIBLE_BUCKET', ''),
            'endpoint' =>  env('S3_COMPATIBLE_ENDPOINT', ''),
            'key' =>  env('S3_COMPATIBLE_KEY', ''),
            'secret' =>  env('S3_COMPATIBLE_SECRET', ''),
            'region' =>  env('S3_COMPATIBLE_REGION', ''),
            'version' =>  env('S3_COMPATIBLE_VERSION', ''),
        ],
        'selectel' => [
            'username' => env('SELECTEL_USERNAME'),
            'password' => env('SELECTEL_PASSWORD'),
            'container' => env('SELECTEL_CONTAINER'),
        ],
    ],
    'images-path' => [
        /* используется для локального адаптера в Flysystem */
        'root-path' => '/var/www/static/dynamicus/', /* /var/www/translation/000/000/000/001/1.jpg */
        /* для временного аплоада и манипуляций с имиджами */
        'absolute-tmp-path' => '/tmp/images/', /* /tmp/images/translation/000/000/001/1.jpg */
        /* используется в ответе, подставляется в путь к имиджам */
        'relative-url' => '/dynamicus/', /* /dynamicus/translation/000/000/000/001/1.jpg */
    ],
    'containers' => [
        'redis' => [
            'host' => env('REDIS_HOST'),
            'port' => env('REDIS_PORT'),
            'password' => env('REDIS_PASS')
        ]
    ]
];