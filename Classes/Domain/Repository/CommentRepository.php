<?php
namespace KoninklijkeCollective\KoningComments\Domain\Repository;

/**
 * Comment repository
 *
 * @package KoninklijkeCollective\KoningComments\Domain\Repository
 */
class CommentRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * @var array
     */
    protected $defaultOrderings = ['date' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING];

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\QueryInterface
     */
    public function createQuery()
    {
        $query = parent::createQuery();
        $query->getQuerySettings()->setStoragePageIds([$this->getTypoScriptFrontendController()->contentPid]);
        return $query;
    }

    /**
     * @param string $url
     * @param string $sort
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findTopLevelCommentsByUrl($url, $sort)
    {
        $query = $this->createQuery();
        $constraints = [];
        $constraints[] = $query->equals('url', $url);
        $constraints[] = $query->equals('replyTo', '');
        $query->setOrderings(['date' => $sort]);
        return $query->matching($query->logicalAnd($constraints))->execute();
    }

    /**
     * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
     */
    protected function getTypoScriptFrontendController()
    {
        return $GLOBALS['TSFE'];
    }
}
