<?php

namespace KoninklijkeCollective\KoningComments\Domain\Model;

use DateTimeInterface;
use TYPO3\CMS\Extbase\Domain\Model\FrontendUser;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Model: Comment
 */
class Comment extends AbstractEntity
{
    public const TABLE = 'tx_koningcomments_domain_model_comment';

    /** @var \DateTime */
    protected $date;

    /** @var bool */
    protected $hidden = false;

    /** @var string */
    protected $url = '';

    /** @var string */
    protected $body = '';

    /** @var \TYPO3\CMS\Extbase\Domain\Model\FrontendUser */
    protected $user;

    /** @var \KoninklijkeCollective\KoningComments\Domain\Model\Comment */
    protected $replyTo;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\KoninklijkeCollective\KoningComments\Domain\Model\Comment>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected $replies;

    /**
     * @return void
     */
    public function initializeObject(): void
    {
        $this->replies = new ObjectStorage();
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param  \DateTimeInterface  $date
     * @return void
     */
    public function setDate(DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param  string  $url
     * @return void
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param  string  $body
     * @return void
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Domain\Model\FrontendUser|null
     */
    public function getUser(): ?FrontendUser
    {
        return $this->user;
    }

    /**
     * @param  \TYPO3\CMS\Extbase\Domain\Model\FrontendUser  $user
     * @return void
     */
    public function setUser(FrontendUser $user): void
    {
        $this->user = $user;
    }

    /**
     * @return \KoninklijkeCollective\KoningComments\Domain\Model\Comment|null
     */
    public function getReplyTo(): ?Comment
    {
        return $this->replyTo;
    }

    /**
     * @param  \KoninklijkeCollective\KoningComments\Domain\Model\Comment|null  $replyTo
     * @return void
     */
    public function setReplyTo(?Comment $replyTo = null): void
    {
        $this->replyTo = $replyTo;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getReplies(): ObjectStorage
    {
        if ($this->replies instanceof LazyLoadingProxy) {
            $this->replies->_loadRealInstance();
        }

        return $this->replies;
    }

    /**
     * @param  \TYPO3\CMS\Extbase\Persistence\ObjectStorage  $replies
     * @return void
     */
    public function setReplies(ObjectStorage $replies): void
    {
        $this->replies = $replies;
    }

    /**
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * @param  bool  $hidden
     * @return void
     */
    public function setHidden(bool $hidden): void
    {
        $this->hidden = $hidden;
    }
}
