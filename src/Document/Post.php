<?php

namespace App\Document;

use DateTime;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use App\Repository\PostRepository;

/**
 * @MongoDB\Document(collection="post", repositoryClass=PostRepository::class)
 * @MongoDB\HasLifecycleCallbacks
 */
class Post extends BaseDocument
{
    /**
     * @MongoDB\Field(type="string")
     */
    private ?string $title = null;

    /**
     * @MongoDB\Field(type="string")
     */
    private ?string $content = null;

    /**
     * @MongoDB\Field(type="int")
     */
    private ?int $status = null;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return self
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return self
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
