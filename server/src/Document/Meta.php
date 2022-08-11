<?php

namespace App\Document;

use DateTime;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use App\Repository\MetaRepository;

/**
 * @MongoDB\Document(collection="meta", repositoryClass=MetaRepository::class)
 * @MongoDB\HasLifecycleCallbacks
 */
class Meta extends BaseDocument
{
    /**
     * @MongoDB\Field(type="string")
     */
    private ?string $post_id = null;

    /**
     * @MongoDB\Field(type="string")
     */
    private ?string $key = null;

    /**
     * @MongoDB\Field(type="string")
     */
    private ?string $value = null;

    /**
     * @return string
     */
    public function getPostId(): string
    {
        return $this->post_id;
    }

    /**
     * @param string $postId
     * @return self
     */
    public function setPostId(string $postId): self
    {
        $this->post_id = $postId;

        return $this;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
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
     * @return self
     */
    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
