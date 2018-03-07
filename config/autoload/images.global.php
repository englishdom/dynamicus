<?php

define('WIDTH', 'width');
define('HEIGHT', 'height');
define('QUALITY', 'quality');
define('TYPE_JPG', 'jpg');

return [
    'images-path' => [
        /* используется для локального адаптера в Flysystem */
        'root-path' => '/var/www/static/dynamicus/', /* /var/www/translation/000/000/000/001/1.jpg */
        /* для временного аплоада и манипуляций с имиджами */
        'absolute-tmp-path' => '/tmp/images/', /* /tmp/images/translation/000/000/001/1.jpg */
        /* используется в ответе, подставляется в путь к имиджам */
        'relative-url' => '/dynamicus/', /* /dynamicus/translation/000/000/000/001/1.jpg */
    ],
    'images' => [
        'translation' => [
            'default' => [
                [WIDTH => 66, HEIGHT => 50, QUALITY => 85], // размер на списке слов
                [WIDTH => 240, HEIGHT => 168, QUALITY => 85], // размер для карточки слова
                [WIDTH => 600, HEIGHT => 432, QUALITY => 85], // размер для тренировки для мобильной версии
            ],
        ],
        'word-set' => [
            'default' => [
                [WIDTH => 180, HEIGHT => 115, QUALITY => 85], // skills/glossary/ список вордсетов
                [WIDTH => 300, HEIGHT => 190, QUALITY => 85], // Картинка на /home в списке заданий
            ]
        ],
        'grammar' => [
            'default' => [
                [WIDTH => 300, HEIGHT => 190, QUALITY => 85], // Картинка на /home в списке заданий
                [WIDTH => 300, HEIGHT => 194, QUALITY => 85], // Картинка на /grammarcards
            ],
        ],
        'video-practicum' => [
            'default' => [
                [WIDTH => 300, HEIGHT => 190, QUALITY => 85], // Картинка на /home в списке заданий
                [WIDTH => 300, HEIGHT => 194, QUALITY => 85], // Картинка на /video-practicum
            ],
        ],
        'course-int' => [
            'default' => [
                [WIDTH => 960, HEIGHT => 172, QUALITY => 85], // Course lesson header new image
            ],
        ],
        'exercise' => [
            'default' => [
                [WIDTH => 66, HEIGHT => 50, QUALITY => 85], // размер на списке слов
                [WIDTH => 240, HEIGHT => 168, QUALITY => 85], // размер для карточки слова
                [WIDTH => 600, HEIGHT => 432, QUALITY => 85], // размер для тренировки для мобильной версии
                [WIDTH => 940, HEIGHT => 293, QUALITY => 85], // Exercise, LifeStory
                [WIDTH => 940, HEIGHT => 532, QUALITY => 85], // Exercise, LifeStory
            ],
        ],
        'ed-class' => [
            'default' => [
                [WIDTH => 300, HEIGHT => 181, QUALITY => 85], // Ed Class small image
                [WIDTH => 940, HEIGHT => 384, QUALITY => 85], // Ed Class big image internal
            ]
        ],
        'ed-class-lesson' => [
            'default' => [
                [WIDTH => 940, HEIGHT => 172, QUALITY => 85], // Ed lesson lesson internal
                [WIDTH => 175, HEIGHT => 230, QUALITY => 85], // Ed lesson lesson
            ]
        ],
        'meta_info' => [
            'og_image' => [WIDTH => 1200, HEIGHT => 628, QUALITY => 85],
            'vk_image' => [WIDTH => 537, HEIGHT => 240, QUALITY => 85],
            'tw_image' => [WIDTH => 1024, HEIGHT => 512, QUALITY => 85],
        ]
    ]
];

// [100, 100], //Undefined size
