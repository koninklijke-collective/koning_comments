<?php

namespace KoninklijkeCollective\KoningComments\Service;

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class PageCacheService
{
    public const CACHE_KEY = 'koning_comments';

    /**
     * @param  string|null  $url
     * @param  \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController|null  $typoScriptFrontendController
     * @return void
     */
    public function addCacheTags(
        ?string $url = null,
        ?TypoScriptFrontendController $typoScriptFrontendController = null
    ): void {
        $typoScriptFrontendController = $typoScriptFrontendController ?? $GLOBALS['TSFE'];
        if (!$typoScriptFrontendController instanceof TypoScriptFrontendController) {
            return;
        }

        $tags = [self::CACHE_KEY];
        if ($url !== null) {
            $tags[] = $this->getCacheTag($url);
        }

        $configuredTags = $typoScriptFrontendController->getPageCacheTags();
        foreach ($tags as $tag) {
            if (!in_array($tag, $configuredTags, true)) {
                $typoScriptFrontendController->addCacheTags([$tag]);
            }
        }
    }

    /**
     * @param  string  $url
     * @return string
     */
    public function getCacheTag(string $url): string
    {
        return self::CACHE_KEY . '_' . md5($url);
    }

    /**
     * Clear page cache where comment is used
     *
     * @param  string  $url
     * @return void
     */
    public function clearCache(string $url): void
    {
        $tag = $this->getCacheTag($url);

        /** @var $cacheManager \TYPO3\CMS\Core\Cache\CacheManager */
        $cacheManager = GeneralUtility::makeInstance(CacheManager::class);

        // Loop all page caches for flushByTag
        foreach (['cache_pages', 'cache_pagesection'] as $cache) {
            try {
                $cacheManager->getCache($cache)->flushByTag($tag);
            } catch (NoSuchCacheException $e) {
            }
        }
    }
}
