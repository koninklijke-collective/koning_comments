<?php

namespace KoninklijkeCollective\KoningComments\ViewHelpers;

use KoninklijkeCollective\KoningComments\Domain\Repository\CommentRepository;
use KoninklijkeCollective\KoningComments\Service\PageCacheService;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class CommentCountViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /** @var \KoninklijkeCollective\KoningComments\Domain\Repository\CommentRepository */
    protected $repository;

    /** @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController */
    protected $typoScriptFrontendController;

    /** @var \KoninklijkeCollective\KoningComments\Service\PageCacheService */
    protected $pageCacheService;

    /**
     * @param  \KoninklijkeCollective\KoningComments\Domain\Repository\CommentRepository  $repository
     * @param  \KoninklijkeCollective\KoningComments\Service\PageCacheService  $pageCacheService
     * @param  \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController  $typoScriptFrontendController
     */
    public function __construct(
        CommentRepository $repository,
        PageCacheService $pageCacheService,
        ?TypoScriptFrontendController $typoScriptFrontendController = null
    ) {
        $this->repository = $repository;
        $this->pageCacheService = $pageCacheService;
        $this->typoScriptFrontendController = $typoScriptFrontendController ?? $GLOBALS['TSFE'];
    }

    /**
     * @return void
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('url', 'string', 'Url of the comments to count', true);
    }

    /**
     * @return int
     */
    public function render(): int
    {
        $url = $this->arguments['url'] ?? '';
        
        // Add cache tag for cleaning cache when created/adjusted
        $this->pageCacheService->addCacheTags($url, $this->typoScriptFrontendController);

        return $this->repository->countByUrl($this->arguments['url']);
    }
}
