<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\KeyValueRepository")
 */
class KeyValue
{
    use TimestampableEntity, SoftDeleteableEntity;


    /**
     * @ORM\Column(name="`key`", type="string", length=20, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue("NONE")
     */
    private $key;

    /**
     * @ORM\Column(name="value", type="text", nullable=false)
     */
    private $value;

    public function getKey(): ?string
    {
        return $this->key;
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

}
