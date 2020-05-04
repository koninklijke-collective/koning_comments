<?php

namespace KoninklijkeCollective\KoningComments\ViewHelpers\Widget\Controller;

use DateTime;
use Exception;
use KoninklijkeCollective\KoningComments\Domain\Model\Comment;
use KoninklijkeCollective\KoningComments\Domain\Repository\CommentRepository;
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
                ->setTargetPageUid($this->getTypoScriptFrontendController()->id)
                ->setCreateAbsoluteUri(true)
                ->build();
    }

    /**
     * @return void
     */
    public function indexAction(): void
    {
        $this->view->assignMultiple([
            'comments' => $this->getCommentRepository()->findTopLevelCommentsByUrl($this->url, $this->sort),
            'enableCommenting' => $this->enableCommenting,
            'userIsLoggedIn' => $this->getTypoScriptFrontendController()->fe_user->user['uid'] > 0,
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
        try {
            $userUid = $this->getTypoScriptFrontendController()->fe_user->user['uid'];

            $settings = $this->getSettings();
            $moderationEnabled = (bool)($settings['enableModeration'] ?? false);

            /** @var \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $user */
            $user = $this->getFrontendUserRepository()->findByUid($userUid);
            if ($user !== null) {
                $comment = new Comment();
                $comment->setBody($body);
                $comment->setUrl($this->url);
                $comment->setUser($user);
                $comment->setPid($this->getTypoScriptFrontendController()->contentPid);
                $comment->setReplyTo($replyTo);
                $comment->setHidden($moderationEnabled);
                $comment->setDate(new DateTime());
                $this->getCommentRepository()->add($comment);
                $this->getPersistenceManager()->persistAll();

                // Now dispatch so other can use this
                $this->signalSlotDispatcher->dispatch(self::class, 'afterCommentCreated', [$this->settings, $comment]);

                // Redirect to current url with comment id as hash to invoke browser scrolling
                HttpUtility::redirect($this->url . '#koning-comment-' . $comment->getUid());
            }
        } catch (Exception $e) {
            // Do nothing
        }

        // Always fallback on default url redirect
        HttpUtility::redirect($this->url);
    }

    /**
     * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
     */
    protected function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }

    /**
     * @return \KoninklijkeCollective\KoningComments\Domain\Repository\CommentRepository
     */
    protected function getCommentRepository(): CommentRepository
    {
        /** @var \KoninklijkeCollective\KoningComments\Domain\Repository\CommentRepository $commentRepository */
        $commentRepository = $this->objectManager->get(CommentRepository::class);

        return $commentRepository;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository
     */
    protected function getFrontendUserRepository(): FrontendUserRepository
    {
        /** @var \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository $frontendUserRepository */
        $frontendUserRepository = $this->objectManager->get(FrontendUserRepository::class);

        return $frontendUserRepository;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
     */
    protected function getPersistenceManager(): PersistenceManagerInterface
    {
        /** @var \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface $persistenceManager */
        $persistenceManager = $this->objectManager->get(PersistenceManagerInterface::class);

        return $persistenceManager;
    }

    /**
     * @return array
     */
    protected function getSettings(): array
    {
        return $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'KoningComments'
        );
    }
}
