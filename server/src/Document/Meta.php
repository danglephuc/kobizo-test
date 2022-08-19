<?php

namespace App\Document;

use DateTime;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use App\Repository\MetaRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @MongoDB\Document(collection="meta", repositoryClass=MetaRepository::class)
 * @MongoDB\HasLifecycleCallbacks
 */
class Meta extends BaseDocument
{
    /**
     * @Groups({"default", "meta"})
     * @MongoDB\Field(type="string")
     */
    private ?string $key = null;

    /**
     * @Groups({"default", "meta"})
     * @MongoDB\Field(type="string")
     */
    private ?string $value = null;

    /**
     * @Groups({"meta_post"})
     * @MongoDB\ReferenceOne(targetDocument=Post::class, inversedBy="meta")
     */
    protected ?Post $post;

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     *
     * @return self
     */
    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param Post $post
     *
     * @return $this
     */
    public function setPost(Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    /**
     * @return Post|null
     */
    public function getPost(): ?Post
    {
        return $this->post;
    }
}
