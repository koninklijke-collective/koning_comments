<?php

namespace KoninklijkeCollective\KoningComments\ViewHelpers\Widget\Controller;

use DateTime;
use KoninklijkeCollective\KoningComments\Domain\Model\Comment;
use KoninklijkeCollective\KoningComments\Domain\Repository\CommentRepository;
use KoninklijkeCollective\KoningComments\Service\PageCacheService;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;
use TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetController;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class CommentsController extends AbstractWidgetController
{
    /** @var string */
    protected $url;

    /** @var bool */
    protected $enableCommenting;

    /** @var string */
    protected $sort;

    /** @var array */
    protected $settings = ['enableModeration' => false];

    /** @var \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface */
    private $persistenceManager;

    /** @var \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository */
    private $frontendUserRepository;

    /** @var \KoninklijkeCollective\KoningComments\Domain\Repository\CommentRepository */
    private $commentRepository;

    /** @var mixed|\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController */
    private $typoScriptFrontendController;

    /** @var \TYPO3\CMS\Core\Context\UserAspect */
    private $frontendUserAspect;

    /** @var \KoninklijkeCollective\KoningComments\Service\PageCacheService */
    private $pageCacheService;

    /**
     * @param  \TYPO3\CMS\Core\Context\Context  $context
     * @param  \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface  $configurationManager
     * @param  \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface  $persistenceManager
     * @param  \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository  $frontendUserRepository
     * @param  \KoninklijkeCollective\KoningComments\Domain\Repository\CommentRepository  $commentRepository
     * @param  \KoninklijkeCollective\KoningComments\Service\PageCacheService  $pageCacheService
     * @param  \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController|null  $typoScriptFrontendController
     * @throws \TYPO3\CMS\Core\Context\Exception\AspectNotFoundException
     */
    public function __construct(
        Context $context,
        ConfigurationManagerInterface $configurationManager,
        PersistenceManagerInterface $persistenceManager,
        FrontendUserRepository $frontendUserRepository,
        CommentRepository $commentRepository,
        PageCacheService $pageCacheService,
        ?TypoScriptFrontendController $typoScriptFrontendController = null
    ) {
        parent::__construct();
        $this->frontendUserAspect = $context->getAspect('frontend.user');
        $this->settings = $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'KoningComments'
        );
        $this->persistenceManager = $persistenceManager;
        $this->frontendUserRepository = $frontendUserRepository;
        $this->commentRepository = $commentRepository;
        $this->pageCacheService = $pageCacheService;
        $this->typoScriptFrontendController = $typoScriptFrontendController ?? $GLOBALS['TSFE'];
    }

    /**
     * @return void
     */
    public function initializeAction(): void
    {
        $this->enableCommenting = $this->widgetConfiguration['enableCommenting'] ?? true;
        $this->sort = $this->widgetConfiguration['sort'] ?? 'DESC';
        $this->url = $this->widgetConfiguration['url']
            ?: $this->uriBuilder
                ->reset()
                ->setTargetPageUid($this->typoScriptFrontendController->id)
                ->setCreateAbsoluteUri(true)
                ->build();
    }

    /**
     * @return void
     */
    public function indexAction(): void
    {
        // Add cache tag for cleaning cache when created/adjusted
        $this->pageCacheService->addCacheTags($this->url);

        $this->view->assignMultiple([
            'comments' => $this->commentRepository->findTopLevelCommentsByUrl($this->url, $this->sort),
            'enableCommenting' => $this->enableCommenting,
            'userIsLoggedIn' => $this->frontendUserAspect->isLoggedIn(),
            'argumentPrefix' => $this->controllerContext->getRequest()->getArgumentPrefix(),
        ]);
    }

    /**
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty", param="body")
     * @param  string  $body
     * @param  \KoninklijkeCollective\KoningComments\Domain\Model\Comment  $replyTo
     * @return void
     */
    public function createAction(string $body, ?Comment $replyTo = null): void
    {
        $comment = $this->createComment($this->frontendUserAspect->get('id'), $body, $replyTo);
        if ($comment !== null) {
            // Redirect to current url with comment id as hash to invoke browser scrolling
            HttpUtility::redirect($this->url . '#koning-comment-' . $comment->getUid());
        }

        // Always fallback on default url redirect
        HttpUtility::redirect($this->url);
    }

    /**
     * @param  int  $userId
     * @param  string  $body
     * @param  \KoninklijkeCollective\KoningComments\Domain\Model\Comment|null  $replyTo
     * @return \KoninklijkeCollective\KoningComments\Domain\Model\Comment|null
     */
    protected function createComment(int $userId, string $body, ?Comment $replyTo = null): ?Comment
    {
        try {
            $this->signalSlotDispatcher->dispatch(
                self::class,
                'beforeCommentCreated',
                [$this->settings, $userId, &$body, &$replyTo]
            );

            $body = trim($body);
            if (empty($body)) {
                return null;
            }

            /** @var \TYPO3\CMS\Extbase\Domain\Model\FrontendUser|null $user */
            $user = $this->frontendUserRepository->findByUid($userId);
            if ($user === null) {
                return null;
            }

            $comment = new Comment();
            $comment->setBody($body);
            $comment->setUrl($this->url);
            $comment->setUser($user);
            $comment->setPid($this->typoScriptFrontendController->contentPid);
            $comment->setReplyTo($replyTo);
            $comment->setHidden((bool)($this->settings['enableModeration'] ?? false));
            $comment->setDate(new DateTime());
            $this->commentRepository->add($comment);
            $this->persistenceManager->persistAll();

            $this->pageCacheService->clearCache($this->url);

            // Now dispatch so other can use this
            $this->signalSlotDispatcher->dispatch(
                self::class,
                'afterCommentCreated',
                [$this->settings, $comment]
            );

            return $comment;
        } catch (\Exception $e) {
            return null;
        }
    }
}
