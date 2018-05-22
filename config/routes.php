<?php
/* @var $app \Zend\Expressive\Application */

/* GET /list/translation/34 */
$app->route(
    '/list/{entity}/{entity_id}[/]',
    [
        \Common\Middleware\PrepareDataObjectMiddleware::class,
        // \Common\Middleware\PrepareFilesystemMiddleware::class,
        \Common\Middleware\ShardingMiddleware::class,
        Dynamicus\Action\ListAction::class
    ],
    ['GET'],
    'list'
)->setOptions([
    'tokens' => [
        'entity' => '\w+',
        'entity_id' => '[\d\,]+'
    ],
]);

/* DELETE /translation/34 */
$app->route(
    '/{entity}/{entity_id}[/]',
    [
        \Common\Middleware\PrepareDataObjectMiddleware::class,
        \Common\Middleware\PrepareFilesystemMiddleware::class,
        \Common\Middleware\ShardingMiddleware::class,
        Dynamicus\Action\DeleteAction::class
    ],
    ['DELETE'],
    'delete'
)->setOptions([
    'tokens' => [
        'entity' => '\w+',
        'entity_id' => '\d+'
    ],
]);

/* POST /translation/35 or with namespace /meta_info:og/34 */
$app->route(
    '/{entity}/{entity_id}[/]',
    [
        /* Подготовка DO */
        \Common\Middleware\PrepareDataObjectMiddleware::class,
        /* Чтение json массива из body */
        \Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware::class,
        /* Сверка разрешенных размеров имиджа с конфигом */
        \Dynamicus\Middleware\CheckImageSizeMiddleware::class,
        /* Подготовка Flysystem */
        \Common\Middleware\PrepareFilesystemMiddleware::class,
        /* Шардирование */
        \Common\Middleware\ShardingMiddleware::class,
        /* Загрузка имиджа и проверка типа */
        Dynamicus\Middleware\DownloadImageMiddleware::class,
        /* Обработка имиджа */
        Dynamicus\Middleware\ProcessImageMiddleware::class,
        /* Запись имиджа в установленную файловую систему */
        Dynamicus\Middleware\WriteImagesMiddleware::class,
        /* Ответ */
        Dynamicus\Action\PostAction::class,
    ],
    ['POST'],
    'create'
)->setOptions([
    'tokens' => [
        'entity' => '\w+',
        'entity_id' => '\d+'
    ],
]);

/* GET /search/{urlencode('search text')} */
$app->route(
    '/search/{search_text}[/]',
    [
        \Dynamicus\Action\SearchAction::class
    ],
    ['GET'],
    'search'
)->setOptions([
    'tokens' => [
        'search_text' => '\w+',
    ],
]);

/**
 * POST /audio/blog-post/1
 */
$app->route(
    '/audio/{entity}/{entity_id}[/]',
    [
        /* Подготовка DO */
        \Common\Middleware\PrepareDataObjectMiddleware::class,
        /* Чтение json массива из body */
        \Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware::class,
        /* Валидация json данных и создание DTO */
        /* @TODO нужна проверка величины текста */
        \Audicus\Middleware\ValidateAudioParamsMiddleware::class,
        /* Создание md5 хеша из данных */
        \Audicus\Middleware\GenerateHashMiddleware::class,
        /* Шардирование по хешу */
        \Audicus\Middleware\ShardingMiddleware::class,
        /* Проверка хеша в редисе */
        \Audicus\Middleware\CheckHashMiddleware::class,
        /* Подготовка Flysystem */
        \Common\Middleware\PrepareFilesystemMiddleware::class,
        /* Генерация файла, запрос Polly */
        \Audicus\Middleware\GenerateAudioMiddleware::class,
        /* Загрузка файла */
        \Audicus\Middleware\UploadFileMiddleware::class,
        /* Добавление entity:id в redis */
        \Audicus\Middleware\AddEntityToStorageMiddleware::class,
        /* Вывод пути добавленного файла */
        \Audicus\Action\PostAction::class,
    ],
    ['POST'],
    'audio-create'
)->setOptions([
    'tokens' => [
        'entity' => '\w+',
        'entity_id' => '\d+'
    ],
]);

$app->route(
    '/audio/upload/{entity}/{entity_id}[/]',
    [],
    ['POST'],
    'audio-upload'
);

$app->route(
    '/audio/regenerate/{entity}/{entity_id}[/]',
    [],
    ['PATCH'],
    'audio-regenerate'
);

$app->route(
    '/audio/{entity}/{entity_id}[/]',
    [],
    ['GET'],
    'audio-list'
);

$app->route(
    '/audio/{entity}/{entity_id}[/]',
    [],
    ['DELETE'],
    'audio-delete'
);

/* GET /testlog/{type} */
$app->route(
    '/test-log/{type:.*}[/]',
    [
        \Dynamicus\Action\TestLogAction::class
    ],
    ['GET'],
    'test-log'
);
