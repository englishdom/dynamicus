<?php

define('WIDTH', 'width');
define('HEIGHT', 'height');
define('QUALITY', 'quality');
define('TYPE_JPG', 'jpg');
/* ключевые слова */
define('KEY_DEFAULT', 'default');
define('KEY_CONTENT', 'content');

return [
    'images' => [
        'translation' => [
            KEY_DEFAULT => [
                [WIDTH => 66, HEIGHT => 50, QUALITY => 85], // размер на списке слов
                [WIDTH => 240, HEIGHT => 168, QUALITY => 85], // размер для карточки слова
                [WIDTH => 600, HEIGHT => 432, QUALITY => 85], // размер для тренировки для мобильной версии
            ],
        ],
        'word-set' => [
            KEY_DEFAULT => [
                [WIDTH => 180, HEIGHT => 115, QUALITY => 85], // skills/glossary/ список вордсетов
                [WIDTH => 300, HEIGHT => 190, QUALITY => 85], // Картинка на /home в списке заданий
            ]
        ],
        'grammar' => [
            KEY_DEFAULT => [
                [WIDTH => 300, HEIGHT => 190, QUALITY => 85], // Картинка на /home в списке заданий
                [WIDTH => 300, HEIGHT => 194, QUALITY => 85], // Картинка на /grammarcards
            ],
        ],
        'video-practicum' => [
            KEY_DEFAULT => [
                [WIDTH => 300, HEIGHT => 190, QUALITY => 85], // Картинка на /home в списке заданий
                [WIDTH => 300, HEIGHT => 194, QUALITY => 85], // Картинка на /video-practicum
            ],
        ],
        'course-int' => [
            KEY_DEFAULT => [
                [WIDTH => 960, HEIGHT => 172, QUALITY => 85], // Course lesson header new image
            ],
        ],
        'exercise' => [
            KEY_DEFAULT => [
                [WIDTH => 66, HEIGHT => 50, QUALITY => 85], // размер на списке слов
                [WIDTH => 240, HEIGHT => 168, QUALITY => 85], // размер для карточки слова
                [WIDTH => 600, HEIGHT => 432, QUALITY => 85], // размер для тренировки для мобильной версии
                [WIDTH => 940, HEIGHT => 293, QUALITY => 85], // Exercise, LifeStory
                [WIDTH => 940, HEIGHT => 532, QUALITY => 85], // Exercise, LifeStory
            ],
        ],
        'ed-class' => [
            KEY_DEFAULT => [
                [WIDTH => 300, HEIGHT => 181, QUALITY => 85], // Ed Class small image
                [WIDTH => 940, HEIGHT => 384, QUALITY => 85], // Ed Class big image internal
            ]
        ],
        'ed-class-lesson' => [
            KEY_DEFAULT => [
                [WIDTH => 940, HEIGHT => 172, QUALITY => 85], // Ed lesson lesson internal
                [WIDTH => 175, HEIGHT => 230, QUALITY => 85], // Ed lesson lesson
            ]
        ],
        'meta_info' => [
            'og' => [
                [WIDTH => 1200, HEIGHT => 628, QUALITY => 85],
            ],
            'vk' => [
                [WIDTH => 537, HEIGHT => 240, QUALITY => 85],
            ],
            'tw' => [
                [WIDTH => 1024, HEIGHT => 512, QUALITY => 85],
            ],
        ],
        'blog-post' => [
            KEY_DEFAULT => [
                [WIDTH => 300, HEIGHT => 285, QUALITY => 85], // Список блог постов
                [WIDTH => 700, HEIGHT => 445, QUALITY => 85], // В начале блог поста
            ],
            KEY_CONTENT => [
                [WIDTH => 620, HEIGHT => 403, QUALITY => 85], // Внутри блог поста
            ]
        ],
        'faq' => [
            KEY_CONTENT => [
                [WIDTH => 400, HEIGHT => 250, QUALITY => 85], // Внутри раздела FAQ
            ]
        ],
        'lesson-element' => [
            KEY_CONTENT => [
                [WIDTH => 816, HEIGHT => 789, QUALITY => 85], // В уроке need-to-know
            ]
        ],
    ]
];

// [100, 100], //Undefined size
