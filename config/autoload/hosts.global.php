<?php

return [
    'hosts' => [
        'default' => [
            '0' => env('HOST_LOCAL'),
            'faq' => env('HOST_SELECTEL'),
            'course-int-lesson' => env('HOST_SELECTEL'),
        ],
        'cdn' => [
            '0' => env('HOST_LOCAL'),
            'faq' => env('HOST_SELECTEL_CDN'),
            'course-int-lesson' => env('HOST_SELECTEL_CDN'),
        ],
    ],
    'adapters' => [
        '0' => [
            \League\Flysystem\AdapterInterface::class,
        ],
        /* Для entity FAQ будет использвоаться адаптер selectel */
        'faq' => [
            \Common\Container\SelectelAdapterInterface::class,
            \League\Flysystem\AdapterInterface::class,
        ],
        'course-int-lesson' => [
            \Common\Container\SelectelAdapterInterface::class,
            \League\Flysystem\AdapterInterface::class,
        ],
        'teacher' => [
            \Common\Container\SelectelAdapterInterface::class,
            \League\Flysystem\AdapterInterface::class,
        ],
        'headteacher' => [
            \Common\Container\SelectelAdapterInterface::class,
            \League\Flysystem\AdapterInterface::class,
        ]
    ],
];