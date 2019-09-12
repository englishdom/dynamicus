<?php

use ZendDiagnostics\Check\DirWritable;

return [
    'diagnostics' => [
        'dirWritable' => new DirWritable('/var/www/static/dynamicus/'),
    ]
];