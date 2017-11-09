<?php
/**
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Action\HomePageAction::class, 'home');
 * $app->post('/album', App\Action\AlbumCreateAction::class, 'album.create');
 * $app->put('/album/:id', App\Action\AlbumUpdateAction::class, 'album.put');
 * $app->patch('/album/:id', App\Action\AlbumUpdateAction::class, 'album.patch');
 * $app->delete('/album/:id', App\Action\AlbumDeleteAction::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Action\ContactAction::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Action\ContactAction::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Action\ContactAction::class,
 *     Zend\Expressive\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */
/* @var $app \Zend\Expressive\Application */

/* GET /list/translation/34 */
$app->route(
    '/list/{entity}/{entity_id}',
    [Dynamicus\Action\ListAction::class],
    ['GET'],
    'list'
);
/* DELETE /translation/34 */
$app->route(
    '/{entity}/{entity_id}',
    [Dynamicus\Action\DeleteAction::class],
    ['DELETE'],
    'delete'
);
/* POST /translation/35 */
$app->route(
    '/{entity}/{entity_id}',
    [
        \Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware::class,
        \Dynamicus\Middleware\DownloadImageMiddleware::class,
        \Dynamicus\Middleware\ProcessImageMiddleware::class,
//        \Dynamicus\Middleware\WriteImagesMiddleware::class,
    ],
    ['POST'],
    'create'
);
