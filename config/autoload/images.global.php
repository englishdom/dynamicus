<?php

const WIDTH = 'width';
const HEIGHT = 'height';
const COMPRESSION_QUALITY = 'quality';

return [
    'images-path' => [
        /* используется для локального адаптера в Flysystem */
        'root-path' => '/var/www/', /* /var/www/translation/000/000/000/001/1.jpg */
        /* для временного аплоада и манипуляций с имиджами */
        'absolute-tmp-path' => '/tmp/images/', /* /tmp/images/translation/000/000/001/1.jpg */
        /* используется в ответе, подставляется в путь к имиджам */
        'relative-url' => '/images/', /* /images/translation/000/000/000/001/1.jpg */
    ],
    'images' => [
        'translation' => [
            'default' => [
                [WIDTH => 66, HEIGHT => 50, COMPRESSION_QUALITY => 80], // skills/glossary/learning/18014/ translations images in list
                [WIDTH => 240, HEIGHT => 168, COMPRESSION_QUALITY => 80], // skills/glossary/learning/18014/#word/like images for translations
            ],
        ],
        'word-set' => [
            'default' => [
                [WIDTH => 180, HEIGHT => 115, COMPRESSION_QUALITY => 80], // skills/glossary/ word sets list
            ]
        ],
        'course-int' => [
            'default' => [
                [WIDTH => 960, HEIGHT => 172, COMPRESSION_QUALITY => 80], // Course lesson header new image
            ],
            'exercise' => [
                [WIDTH => 940, HEIGHT => 532, COMPRESSION_QUALITY => 80], // Courses-int, exercises, group B
                [WIDTH => 600, HEIGHT => 432, COMPRESSION_QUALITY => 80], // Courses-int, exercises, group C
                [WIDTH => 940, HEIGHT => 293, COMPRESSION_QUALITY => 80], // Courses-int, LifeStory
            ]
        ],
        'ed-class' => [
            'default' => [
                [WIDTH => 300, HEIGHT => 181, COMPRESSION_QUALITY => 80], // Ed Class small image
                [WIDTH => 940, HEIGHT => 384, COMPRESSION_QUALITY => 80], // Ed Class big image
                [WIDTH => 940, HEIGHT => 172, COMPRESSION_QUALITY => 80], // Ed lesson
            ]
        ]
    ]
];

// [100, 100], //Undefined size
// [300, 194], //Undefined size
// [300, 190], //Undefined size
