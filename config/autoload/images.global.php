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
                [WIDTH => 66, HEIGHT => 50], // размер на списке слов
                [WIDTH => 240, HEIGHT => 168, QUALITY => 90], // размер для карточки слова
                [WIDTH => 256, HEIGHT => 190, QUALITY => 90], // размер для тренировки для веб версии
                [WIDTH => 600, HEIGHT => 432, QUALITY => 80], // размер для тренировки для мобильной версии
            ],
        ],
        'word-set' => [
            'default' => [
                [WIDTH => 180, HEIGHT => 115, QUALITY => 80], // skills/glossary/ список вордсетов
                [WIDTH => 220, HEIGHT => 141, QUALITY => 80], // Картинка на /home в списке заданий
            ]
        ],
        'course-int' => [
            'default' => [
                [WIDTH => 960, HEIGHT => 172, QUALITY => 80], // Course lesson header new image
            ],
            'exercise' => [
                [WIDTH => 940, HEIGHT => 532, QUALITY => 80], // Courses-int, exercises, group B
                [WIDTH => 600, HEIGHT => 432, QUALITY => 80], // Courses-int, exercises, group C
                [WIDTH => 940, HEIGHT => 293, QUALITY => 80], // Courses-int, LifeStory
            ]
        ],
        'ed-class' => [
            'default' => [
                [WIDTH => 300, HEIGHT => 181, QUALITY => 80], // Ed Class small image
                [WIDTH => 940, HEIGHT => 384, QUALITY => 80], // Ed Class big image
                [WIDTH => 940, HEIGHT => 172, QUALITY => 80], // Ed lesson
            ]
        ]
    ]
];

// [100, 100], //Undefined size
// [300, 194], //Undefined size
// [300, 190], //Undefined size
