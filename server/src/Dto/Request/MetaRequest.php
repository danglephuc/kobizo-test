<?php

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

class MetaRequest
{
    /**
     * @var string
     *
     * @Assert\IsNull(
     *     groups={"OpCreate"},
     * )
     * @Assert\NotNull(
     *     groups={"OpUpdate"},
     * )
     */
    private string $id;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     groups={"OpCreate", "OpUpdate"},
     * )
     */
    private string $key;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     groups={"OpCreate", "OpUpdate"},
     * )
     */
    private string $value;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
