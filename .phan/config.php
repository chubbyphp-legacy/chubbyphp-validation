<?php

return [
    "target_php_version" => null,
    'directory_list' => [
        'src',
        'vendor/doctrine/common',
        'vendor/pimple/pimple',
        'vendor/psr/container',
        'vendor/psr/log',
        'vendor/symfony/validator'
    ],
    "exclude_analysis_directory_list" => [
        'vendor/'
    ],

    'plugins' => [
        'AlwaysReturnPlugin',
        'UnreachableCodePlugin',
        'DollarDollarPlugin',
        'DuplicateArrayKeyPlugin',
        'PregRegexCheckerPlugin',
        'PrintfCheckerPlugin',
    ],
];
