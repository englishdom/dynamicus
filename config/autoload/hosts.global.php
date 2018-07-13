<?php

return [
    'hosts' => [
        '0' => env('HOST_LOCAL'),
        'faq' => env('HOST_SELECTEL'),
        'course-int-lesson' => env('HOST_SELECTEL'),
    ],
    'adapters' => [
        '0' => \League\Flysystem\AdapterInterface::class,
        /* Для entity FAQ будет использвоаться адаптер selectel */
        'faq' => \Common\Container\SelectelAdapterInterface::class,
        'course-int-lesson' => \Common\Container\SelectelAdapterInterface::class,
    ],

];