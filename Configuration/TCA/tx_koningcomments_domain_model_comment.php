<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}
return call_user_func(function ($extension, $table) {
    return [
        'ctrl' => [
            'title' => 'LLL:EXT:' . $extension . '/Resources/Private/Language/locallang_be.xlf:' . $table,
            'label' => 'date',
            'hideAtCopy' => true,
            'tstamp' => 'tstamp',
            'crdate' => 'crdate',
            'cruser_id' => 'cruser_id',
            'editlock' => 'editlock',
            'dividers2tabs' => true,
            'hideTable' => false,
            'delete' => 'deleted',
            'searchFields' => 'body',
            'enablecolumns' => [
                'disabled' => 'hidden',
            ],
            'typeicon_classes' => [
                'default' => 'tcarecords-tx_koningcomments_domain_model_comment-default',
            ],
        ],
        'interface' => [
            'showRecordFieldList' => 'hidden, date, url, body, user, reply_to, replies',
        ],
        'types' => [
            0 => ['showitem' => 'hidden, date, url, body, user, reply_to, replies'],
        ],
        'palettes' => [],
        'columns' => [
            'hidden' => [
                'exclude' => 1,
                'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
                'config' => [
                    'type' => 'check',
                    'default' => false,
                ],
            ],
            'date' => [
                'exclude' => 0,
                'label' => 'LLL:EXT:' . $extension . '/Resources/Private/Language/locallang_be.xlf:' . $table . '.date',
                'config' => [
                    'type' => 'input',
                    'eval' => 'datetime',
                ],
            ],
            'url' => [
                'exclude' => 0,
                'label' => 'LLL:EXT:' . $extension . '/Resources/Private/Language/locallang_be.xlf:' . $table . '.url',
                'config' => [
                    'type' => 'input',
                ],
            ],
            'body' => [
                'exclude' => 0,
                'label' => 'LLL:EXT:' . $extension . '/Resources/Private/Language/locallang_be.xlf:' . $table . '.body',
                'config' => [
                    'type' => 'text',
                ],
            ],
            'user' => [
                'exclude' => 0,
                'label' => 'LLL:EXT:' . $extension . '/Resources/Private/Language/locallang_be.xlf:' . $table . '.user',
                'config' => [
                    'type' => 'group',
                    'internal_type' => 'db',
                    'allowed' => 'fe_users',
                    'minitems' => 1,
                    'maxitems' => 1,
                    'size' => 1,
                ],
            ],
            'reply_to' => [
                'exclude' => 0,
                'label' => 'LLL:EXT:' . $extension . '/Resources/Private/Language/locallang_be.xlf:' . $table . '.reply_to',
                'config' => [
                    'type' => 'group',
                    'internal_type' => 'db',
                    'allowed' => $table,
                    'minitems' => 0,
                    'maxitems' => 1,
                    'size' => 1,
                ],
            ],
            'replies' => [
                'exclude' => 0,
                'label' => 'LLL:EXT:' . $extension . '/Resources/Private/Language/locallang_be.xlf:' . $table . '.replies',
                'config' => [
                    'type' => 'inline',
                    'foreign_table' => $table,
                    'foreign_field' => 'reply_to',
                    'foreign_sortby' => 'date',
                    'maxitems' => 9999,
                ],
            ],
        ],
    ];
}, 'koning_comments', 'tx_koningcomments_domain_model_comment');
