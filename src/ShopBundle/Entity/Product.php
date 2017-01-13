<?php

namespace ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="ShopBundle\Repository\ProductRepository")
 */
class Product
{

    use TimestampableEntity, SoftDeleteableEntity;


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
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     *
     * @ORM\ManyToOne(targetEntity="ShopBundle\Entity\Caster", inversedBy="products")
     */
    private $caster;


    /**
     *
     * @ORM\ManyToMany(targetEntity="ShopBundle\Entity\Tag", inversedBy="products")
     */
    private $tags;


    /**
     *
     * @ORM\ManyToOne(targetEntity="ShopBundle\Entity\File", inversedBy="product")
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
     * @return Product
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
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set caster
     *
     * @param \ShopBundle\Entity\Caster $caster
     *
     * @return Product
     */
    public function setCaster(\ShopBundle\Entity\Caster $caster = null)
    {
        $this->caster = $caster;

        return $this;
    }

    /**
     * Get caster
     *
     * @return \ShopBundle\Entity\Caster
     */
    public function getCaster()
    {
        return $this->caster;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add tag
     *
     * @param \ShopBundle\Entity\Tag $tag
     *
     * @return Product
     */
    public function addTag(\ShopBundle\Entity\Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \ShopBundle\Entity\Tag $tag
     */
    public function removeTag(\ShopBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set pictures
     *
     * @param \ShopBundle\Entity\File $pictures
     *
     * @return Product
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
