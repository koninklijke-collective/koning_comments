<?php

call_user_func(function ($extension) {
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
        'tcarecords-tx_koningcomments_domain_model_comment-default',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:' . $extension . '/Resources/Public/Icons/tx_koningcomments_domain_model_comment.svg']
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages(\KoninklijkeCollective\KoningComments\Domain\Model\Comment::TABLE);

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $extension,
        'Configuration/TypoScript/',
        'Koning Comments'
    );
}, $_EXTKEY);
