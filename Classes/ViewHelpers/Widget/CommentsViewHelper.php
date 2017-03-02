<?php
namespace KoninklijkeCollective\KoningComments\ViewHelpers\Widget;

/**
 * Comments widget
 *
 * Usage:
 *
 * <c:widget.comments />
 *
 * Options:
 *  - url: link to current page (if left empty, it builds the url based on TSFE->id. Use this param if you use this on a page with an extension with url params
 *  - enableCommenting: use this to make commenting read only (will display previous comments, but no option to post new ones)
 *  - sort: sorting (ASC or DESC)
 *
 * Example with url:
 *
 * <c:widget.comments url="{f:uri.action(action: 'detail', arguments: '{identifier: \'{item.uid}\'}', absolute: 1, noCacheHash: 1)}" />
 *
 * @package KoninklijkeCollective\KoningComments\ViewHelpers\Widget
 */
class CommentsViewHelper extends \TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetViewHelper
{
    /**
     * @var \KoninklijkeCollective\KoningComments\ViewHelpers\Widget\Controller\CommentsController
     * @inject
     */
    protected $controller;

    /**
     * Render commenting functionality
     *
     * @param string $url
     * @param bool $enableCommenting
     * @param string $sort
     * @return \TYPO3\CMS\Extbase\Mvc\ResponseInterface
     */
    public function render(
        $url = '',
        $enableCommenting = true,
        $sort = 'DESC'
    ) {
        return $this->initiateSubRequest();
    }
}
