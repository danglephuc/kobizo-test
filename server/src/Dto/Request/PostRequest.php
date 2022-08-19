<?php

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

class PostRequest
{
    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     groups={"OpCreate", "OpUpdate"},
     * )
     */
    private string $title;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     groups={"OpCreate", "OpUpdate"},
     * )
     */
    private string $content;

    /**
     * @var int
     *
     * @Assert\NotBlank(
     *     groups={"OpCreate", "OpUpdate"},
     * )
     */
    private int $status;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
