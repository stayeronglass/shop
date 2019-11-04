<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\KeyValueRepository")
 */
class KeyValue
{
    use TimestampableEntity, SoftDeleteableEntity;


    /**
     * @ORM\Column(name="`key`", type="string", length=50, nullable=false, unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue("NONE")
     * @Assert\NotBlank()
     * @Assert\Unique()
     */
    private $key;

    
    /**
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private $description;


    /**
     * @ORM\Column(name="value", type="text", nullable=false)
     * @Assert\NotBlank()
     */
    private $value;



    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
