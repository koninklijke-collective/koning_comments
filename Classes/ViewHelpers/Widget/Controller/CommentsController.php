<?php
namespace KoninklijkeCollective\KoningComments\ViewHelpers\Widget\Controller;

/**
 * Comment widget controller
 *
 * @package KoninklijkeCollective\KoningComments\ViewHelpers\Widget\Controller
 */
class CommentsController extends \TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetController
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @var bool
     */
    protected $enableCommenting;

    /**
     * @return void
     */
    public function initializeAction()
    {
        $this->enableCommenting = $this->widgetConfiguration['enableCommenting'];

        if ($this->widgetConfiguration['url'] === '') {
            $this->url = $this->uriBuilder
                ->reset()
                ->setTargetPageUid($this->getTypoScriptFrontendController()->id)
                ->setCreateAbsoluteUri(true)
                ->build();
        } else {
            $this->url = $this->widgetConfiguration['url'];
        }
    }

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->view->assignMultiple([
            'comments' => $this->getCommentRepository()->findTopLevelCommentsByUrl($this->url),
            'enableCommenting' => $this->enableCommenting,
            'userIsLoggedIn' => $this->getTypoScriptFrontendController()->loginUser,
            'argumentPrefix' => $this->controllerContext->getRequest()->getArgumentPrefix()
        ]);
    }

    /**
     * @param string $body
     * @param \KoninklijkeCollective\KoningComments\Domain\Model\Comment $replyTo
     * @validate $body NotEmpty
     * @return void
     */
    public function createAction($body, $replyTo = null)
    {
        $userUid = $this->getTypoScriptFrontendController()->fe_user->user['uid'];

        /** @var \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $user */
        $user = $this->getFrontendUserRepository()->findByUid($userUid);
        if ($user !== null) {
            $comment = new \KoninklijkeCollective\KoningComments\Domain\Model\Comment();
            $comment->setBody($body);
            $comment->setUrl($this->url);
            $comment->setUser($user);
            $comment->setPid($this->getTypoScriptFrontendController()->contentPid);
            $comment->setReplyTo($replyTo);
            $this->getCommentRepository()->add($comment);
        }

        header('Location: ' . $this->url);
    }

    /**
     * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
     */
    protected function getTypoScriptFrontendController()
    {
        return $GLOBALS['TSFE'];
    }

    /**
     * @return \KoninklijkeCollective\KoningComments\Domain\Repository\CommentRepository
     */
    protected function getCommentRepository()
    {
        /** @var \KoninklijkeCollective\KoningComments\Domain\Repository\CommentRepository $commentRepository */
        $commentRepository = $this->objectManager->get(\KoninklijkeCollective\KoningComments\Domain\Repository\CommentRepository::class);
        return $commentRepository;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository
     */
    protected function getFrontendUserRepository()
    {
        /** @var \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository $frontendUserRepository */
        $frontendUserRepository = $this->objectManager->get(\TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository::class);
        return $frontendUserRepository;
    }
}
