<?php

call_user_func(function ($extension, $table): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $extension,
        'Configuration/TypoScript/',
        'Koning Comments'
    );
}, 'koning_comments', 'sys_template');
