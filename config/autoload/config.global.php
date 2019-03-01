<?php

return [
    // для поиска картинок в гугл
    'google-api' => [
        'default' => [
            'key' => env('GOOGLE_API_KEY'),
            'cx' => env('GOOGLE_API_CX'),
        ],
        /* расширение для хрома */
        'ed-translator' => [
            'key' => env('GOOGLE_EDTRANSLATOR_API_KEY'),
            'cx' => env('GOOGLE_EDTRANSLATOR_API_CX'),
        ],
        /* веб */
        'web' => [
            'key' => env('GOOGLE_WEB_API_KEY'),
            'cx' => env('GOOGLE_WEB_API_CX'),
        ],
        /* мобильный клиент */
        'ed-words' => [
            'key' => env('GOOGLE_EDWORDS_API_KEY'),
            'cx' => env('GOOGLE_EDWORDS_API_CX'),
        ],
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
        'root-path' => '/var/www/static/',
        /* название корневой директории */
        'root-name' => 'dynamicus',
        /* для временного аплоада и манипуляций с имиджами */
        'absolute-tmp-path' => '/tmp/images/', /* /tmp/images/translation/000/000/001/1.jpg */
    ],
];