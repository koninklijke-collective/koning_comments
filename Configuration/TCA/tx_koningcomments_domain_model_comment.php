<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:koning_comments/Resources/Private/Language/locallang_be.xlf:tx_koningcomments_domain_model_comment',
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
        'default_sortby' => 'date DESC',
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
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:pages.hidden_toggle',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 1,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],
        'date' => [
            'exclude' => false,
            'label' => 'LLL:EXT:koning_comments/Resources/Private/Language/locallang_be.xlf:tx_koningcomments_domain_model_comment.date',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
            ],
        ],
        'url' => [
            'exclude' => false,
            'label' => 'LLL:EXT:koning_comments/Resources/Private/Language/locallang_be.xlf:tx_koningcomments_domain_model_comment.url',
            'config' => [
                'type' => 'input',
            ],
        ],
        'body' => [
            'exclude' => false,
            'label' => 'LLL:EXT:koning_comments/Resources/Private/Language/locallang_be.xlf:tx_koningcomments_domain_model_comment.body',
            'config' => [
                'type' => 'text',
            ],
        ],
        'user' => [
            'exclude' => false,
            'label' => 'LLL:EXT:koning_comments/Resources/Private/Language/locallang_be.xlf:tx_koningcomments_domain_model_comment.user',
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
            'exclude' => false,
            'label' => 'LLL:EXT:koning_comments/Resources/Private/Language/locallang_be.xlf:tx_koningcomments_domain_model_comment.reply_to',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => \KoninklijkeCollective\KoningComments\Domain\Model\Comment::TABLE,
                'minitems' => 0,
                'maxitems' => 1,
                'size' => 1,
            ],
        ],
        'replies' => [
            'exclude' => false,
            'label' => 'LLL:EXT:koning_comments/Resources/Private/Language/locallang_be.xlf:tx_koningcomments_domain_model_comment.replies',
            'config' => [
                'type' => 'inline',
                'foreign_table' => \KoninklijkeCollective\KoningComments\Domain\Model\Comment::TABLE,
                'foreign_field' => 'reply_to',
                'foreign_sortby' => 'date',
                'maxitems' => 9999,
            ],
        ],
    ],
];
