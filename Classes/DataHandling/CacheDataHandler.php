<?php

namespace KoninklijkeCollective\KoningComments\DataHandling;

use KoninklijkeCollective\KoningComments\Domain\Model\Comment;
use KoninklijkeCollective\KoningComments\Service\PageCacheService;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CacheDataHandler
{
    /**
     * @param  array  $parameters
     * @param  \TYPO3\CMS\Core\DataHandling\DataHandler  $reference
     * @return void
     */
    public function clearCachePostProc(array $parameters, DataHandler $reference): void
    {
        ['table' => $table, 'uid' => $uid] = $parameters;
        if ($table !== Comment::TABLE || !is_numeric($uid)) {
            return;
        }

        $url = $reference->datamap[$table][$uid]['url'] ?? '';
        if (!empty($url)) {
            return;
        }

        GeneralUtility::makeInstance(PageCacheService::class)->clearCache($url);
    }
}
