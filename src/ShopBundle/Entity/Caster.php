<?php

namespace ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Caster
 *
 * @ORM\Table(name="caster")
 * @ORM\Entity(repositoryClass="ShopBundle\Repository\CasterRepository")
 */
class Caster
{

    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="vk", type="string", length=255)
     */
    private $vk;


    /**
     *
     * @ORM\ManyToOne(targetEntity="ShopBundle\Entity\File", inversedBy="caster")
     */
    private $pictures;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Caster
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Caster
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set vk
     *
     * @param string $vk
     *
     * @return Caster
     */
    public function setVk($vk)
    {
        $this->vk = $vk;

        return $this;
    }

    /**
     * Get vk
     *
     * @return string
     */
    public function getVk()
    {
        return $this->vk;
    }

    /**
     * Set pictures
     *
     * @param \ShopBundle\Entity\File $pictures
     *
     * @return Caster
     */
    public function setPictures(\ShopBundle\Entity\File $pictures = null)
    {
        $this->pictures = $pictures;

        return $this;
    }

    /**
     * Get pictures
     *
     * @return \ShopBundle\Entity\File
     */
    public function getPictures()
    {
        return $this->pictures;
    }
}
