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
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findTopLevelCommentsByUrl($url)
    {
        $query = $this->createQuery();
        $constraints = [];
        $constraints[] = $query->equals('url', $url);
        $constraints[] = $query->equals('replyTo', '');
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
