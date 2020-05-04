<?php

namespace KoninklijkeCollective\KoningComments\ViewHelpers;

use KoninklijkeCollective\KoningComments\Domain\Repository\CommentRepository;

class CommentCountViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /** @var \KoninklijkeCollective\KoningComments\Domain\Repository\CommentRepository */
    protected $repository;

    /**
     * @param  \KoninklijkeCollective\KoningComments\Domain\Repository\CommentRepository  $repository
     */
    public function __construct(CommentRepository $repository)
    {
        $this->repository = $repository;
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
        return $this->repository->countByUrl($this->arguments['url']);
    }
}
