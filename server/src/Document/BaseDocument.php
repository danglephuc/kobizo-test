<?php

namespace App\Document;

use DateTime;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @MongoDB\Document
 * @MongoDB\HasLifecycleCallbacks
 */
class BaseDocument
{
    /**
     * @Groups("default")
     * @MongoDB\Id
     */
    private ?string $id = null;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @Groups("default")
     * @MongoDB\Field(type="date")
     */
    private ?DateTime $created_at = null;

    /**
     * @Groups("default")
     * @MongoDB\Field(type="date")
     */
    private ?DateTime $updated_at = null;

    public function getCreatedAt(): ?DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTime $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(DateTime $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /** @MongoDB\PrePersist */
    public function setCreatedAtValue(): void
    {
        if (!$this->getCreatedAt()) {
            $this->created_at = new DateTime('now');
        }

        if (!$this->getUpdatedAt()) {
            $this->updated_at = new DateTime('now');
        }
    }

    /** @MongoDB\PreUpdate */
    public function setModifiedAtValue()
    {
        if (!$this->getUpdatedAt()) {
            $this->updated_at = new DateTime('now');
        }
    }
}
