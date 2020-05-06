<?php

call_user_func(function ($extension): void {
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
        'tcarecords-tx_koningcomments_domain_model_comment-default',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:koning_comments/Resources/Public/Icons/tx_koningcomments_domain_model_comment.svg']
    );
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc'][$extension . '_clear_cache'] =
        \KoninklijkeCollective\KoningComments\DataHandling\CacheDataHandler::class . '->clearCachePostProc';
}, 'koning_comments');
