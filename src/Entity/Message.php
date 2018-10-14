<?php

namespace App\Entity;

use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message
{
    use SoftDeleteableEntity, TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $text;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="sender_messages")
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $sender;
    private $sender_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="recipient_id_messages")
     * @ORM\JoinColumn(name="recipient_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $recipient;
    private $recipient_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getRecipient(): ?User
    {
        return $this->recipient;
    }

    public function setRecipient(?User $recipient): self
    {
        $this->recipient = $recipient;

        return $this;
    }

}
