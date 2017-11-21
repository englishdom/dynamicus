<?php
/* @var $app \Zend\Expressive\Application */

/* GET /list/translation/34 */
$app->route(
    '/list/{entity}/{entity_id}',
    [
        \Common\Middleware\PrepareDataObjectMiddleware::class,
        \Common\Middleware\PrepareFilesystemMiddleware::class,
        \Common\Middleware\ShardingMiddleware::class,
        Dynamicus\Action\ListAction::class
    ],
    ['GET'],
    'list'
);
/* DELETE /translation/34 */
$app->route(
    '/{entity}/{entity_id}',
    [
        \Common\Middleware\PrepareDataObjectMiddleware::class,
        \Common\Middleware\PrepareFilesystemMiddleware::class,
        \Common\Middleware\ShardingMiddleware::class,
        Dynamicus\Action\DeleteAction::class
    ],
    ['DELETE'],
    'delete'
);
/* POST /translation/35 */
$app->route(
    '/{entity}/{entity_id}',
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
);
/* GET /search/{urlencode('search text')} */
$app->route(
    '/search/{search_text}',
    [
        \Dynamicus\Action\SearchAction::class
    ],
    ['GET'],
    'search'
);
