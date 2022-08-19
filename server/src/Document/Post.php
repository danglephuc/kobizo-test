<?php

namespace App\Document;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use App\Repository\PostRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @MongoDB\Document(collection="post", repositoryClass=PostRepository::class)
 * @MongoDB\HasLifecycleCallbacks
 */
class Post extends BaseDocument
{
    /**
     * @Groups({"default", "post"})
     * @MongoDB\Field(type="string")
     */
    private ?string $title = null;

    /**
     * @Groups({"default", "post"})
     * @MongoDB\Field(type="string")
     */
    private ?string $content = null;

    /**
     * @Groups({"default", "post"})
     * @MongoDB\Field(type="int")
     */
    private ?int $status = null;

    /**
     * @Groups("post_meta")
     * @MongoDB\ReferenceMany(targetDocument=Meta::class, mappedBy="post")
     */
    private ArrayCollection $meta;

    /**
     * Post constructor.
     */
    public function __construct()
    {
        $this->meta = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
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
     *
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
     *
     * @return self
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getMeta(): ArrayCollection
    {
        return $this->meta;
    }

    /**
     * @param array $meta
     *
     * @return self
     */
    public function setMeta(array $meta): self
    {
        $this->meta = new ArrayCollection($meta);

        return $this;
    }
}
