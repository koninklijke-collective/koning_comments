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
    'version' => '1.1.0',
    'constraints' => [
        'depends' => [
            'typo3' => '>8.7.12, <9.0.0',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
