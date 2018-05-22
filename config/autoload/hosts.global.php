<?php

return [
    'hosts' => [
        0 => env('HOST_LOCAL'),
        'faq' => env('HOST_SELECTEL'),
    ],
    'adapters' => [
        0 => \Common\Factory\FilesystemLocalFSAdapterFactory::class,
        /* Для entity FAQ будет использвоаться адаптер selectel */
        'faq' => \Common\Factory\FilesystemSelectelAdapterFactory::class,
    ]
];