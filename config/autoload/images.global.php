<?php

define('WIDTH', 'width');
define('HEIGHT', 'height');
define('QUALITY', 'quality');
define('TYPE_JPG', 'jpg');
/* ключевые слова */
define('KEY_DEFAULT', 'default');
define('KEY_CONTENT', 'content');
define('KEY_ORIGINAL', 'original');

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
        'course-int-lesson' => [
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
                [WIDTH => 300, HEIGHT => 190, QUALITY => 85], // Вывод на главной "пост из блога"
                [WIDTH => 700, HEIGHT => 445, QUALITY => 100], // В начале блог поста
                [WIDTH => 220, HEIGHT => 138, QUALITY => 85], // вывод на странице поста с права и с низу
                [WIDTH => 180, HEIGHT => 114, QUALITY => 85], // вывод на dashboard
                [WIDTH => 300, HEIGHT => false, QUALITY => 85], // Список блог постов
            ],
            KEY_CONTENT => [
                [WIDTH => 700, HEIGHT => 455, QUALITY => 100], // Внутри блог поста
            ]
        ],
        'blog-author' => [
            KEY_DEFAULT => [
                [WIDTH => 100, HEIGHT => 100, QUALITY => 85],
            ]
        ],
        'faq' => [
            KEY_DEFAULT => [
                [WIDTH => 400, HEIGHT => 250, QUALITY => 85], // Внутри раздела FAQ
            ]
        ],
        'lesson-element' => [
            KEY_CONTENT => [
                // /dynamicus/lesson-element/000/000/001/1522844313_content_0x0.jpg
                // /dynamicus/lesson-element/000/000/001/1522844313_content_0x789.jpg
                // /dynamicus/lesson-element/000/000/001/1522844313_content_816x0.jpg
                // размеры оставляем как есть, но если пришли координаты - тогда РЕЖЕМ
                // {"size":"700x445","crop":"0x0x700x445"}
                // меняем качестово
                // удаляем екзиф инфу
                [WIDTH => 816, HEIGHT => false, QUALITY => 85], // В уроке need-to-know
            ]
        ],
        'user' => [
            KEY_DEFAULT => [
                [WIDTH => 40, HEIGHT => 40, QUALITY => 100], // комментарии
                [WIDTH => 50, HEIGHT => 50, QUALITY => 100], // дашборд. верхний правый угол
                [WIDTH => 140, HEIGHT => 140, QUALITY => 100], // профиль пользователя, просмотр
            ]
        ],
        'teacher' => [
            KEY_DEFAULT => [
                [WIDTH => 300, HEIGHT => 200, QUALITY => 85], // большая фото преподователей
                [WIDTH => 100, HEIGHT => 100, QUALITY => 100], // аватар
            ]
        ],
        'headteacher' => [
            KEY_DEFAULT => [
                [WIDTH => 100, HEIGHT => 100, QUALITY => 100], // аватар
            ]
        ],
        'task-talk' => [
            KEY_DEFAULT => [
                [WIDTH => 940, HEIGHT => 565, QUALITY => 80], // background
                [WIDTH => 70, HEIGHT => 70, QUALITY => 100], // avatar
            ]
        ],
        'certificate' => [
            KEY_ORIGINAL => true // save only original
        ],
        'online-test' => [
            KEY_DEFAULT => [
                [WIDTH => 224, HEIGHT => 168, QUALITY => 85], // слова
            ]
        ],
        'webinar' => [
            KEY_DEFAULT => [
                [WIDTH => 940, HEIGHT => 405, QUALITY => 85], /* Фото вебинара s1 940x405 (бекгрануд) */
                [WIDTH => 300, HEIGHT =>  260, QUALITY => 100], /* Фото вебинара s2 300x260 (будущий/активный вебинар) */
                [WIDTH => 300, HEIGHT => 204, QUALITY => 100], /* Фото вебинара s3 300x204 (архив) */
                [WIDTH => 54, HEIGHT => 54, QUALITY => 100], /* Фотография "спикера" s4 54х54 */
                [WIDTH => 34, HEIGHT => 34, QUALITY => 100]
            ]
        ]
    ]
];

// [100, 100], //Undefined size
