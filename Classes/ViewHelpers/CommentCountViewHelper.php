<?php
namespace KoninklijkeCollective\KoningComments\ViewHelpers;

/**
 * View helper: Comment count
 *
 * @package KoninklijkeCollective\KoningComments\ViewHelpers
 */
class CommentCountViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('url', 'string', 'Url of the comments to count', true);
    }

    /**
     * @return int
     */
    public function render()
    {
        return $this->getCommentRepository()->countByUrl($this->arguments['url']);
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
}
