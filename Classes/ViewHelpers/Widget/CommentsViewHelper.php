<?php

namespace KoninklijkeCollective\KoningComments\ViewHelpers\Widget;

use KoninklijkeCollective\KoningComments\ViewHelpers\Widget\Controller\CommentsController;
use TYPO3\CMS\Extbase\Mvc\ResponseInterface;
use TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetViewHelper;

/**
 * Comments widget
 *
 * Usage:
 *
 * <c:widget.comments />
 *
 * Options:
 *  - url: link to current page (if left empty, it builds the url based on TSFE->id. Use this param if you use this on
 * a page with an extension with url params
 *  - enableCommenting: use this to make commenting read only (will display previous comments, but no option to post
 * new ones)
 *  - sort: sorting (ASC or DESC)
 *
 * Example with url:
 *
 * <c:widget.comments url="{f:uri.action(action: 'detail', arguments: '{identifier: \'{item.uid}\'}', absolute: 1)}" />
 */
class CommentsViewHelper extends AbstractWidgetViewHelper
{
    /** @var \KoninklijkeCollective\KoningComments\ViewHelpers\Widget\Controller\CommentsController */
    protected $controller;

    /**
     * @param  \KoninklijkeCollective\KoningComments\ViewHelpers\Widget\Controller\CommentsController  $controller
     * @return void
     */
    public function injectCommentsController(CommentsController $controller): void
    {
        $this->controller = $controller;
    }

    /**
     * Initialize arguments.
     *
     * @throws \TYPO3Fluid\Fluid\Core\ViewHelper\Exception
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('url', 'string', '', false, '');
        $this->registerArgument('enableCommenting', 'boolean', '', false, true);
        $this->registerArgument('sort', 'string', 'configuration', false, 'DESC');
    }

    /**
     * Render commenting functionality
     *
     * @return \TYPO3\CMS\Extbase\Mvc\ResponseInterface
     */
    public function render(): ResponseInterface
    {
        return $this->initiateSubRequest();
    }
}
