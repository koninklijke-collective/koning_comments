<?php
namespace KoninklijkeCollective\KoningComments\ViewHelpers\Widget;

/**
 * Comments widget
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
        $sort = 'ASC'
    ) {
        return $this->initiateSubRequest();
    }
}
