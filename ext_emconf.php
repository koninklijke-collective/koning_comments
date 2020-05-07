<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Comments',
    'description' => 'Commenting system based on fe_users',
    'category' => 'fe',
    'author' => 'Jesper Paardekooper',
    'author_email' => 'j.paardekooper@develement.nl',
    'state' => 'stable',
    'uploadFolder' => false,
    'clearCacheOnLoad' => true,
    'version' => '2.0.1',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-9.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'createDirs' => '',
    'autoload' => [
        'psr-4' => [
            'KoninklijkeCollective\\KoningComments\\' => 'Classes',
        ],
    ],
];
