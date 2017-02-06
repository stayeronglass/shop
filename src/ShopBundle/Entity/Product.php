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
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 5,
     *      max = 255,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
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
     * @ORM\OneToMany(targetEntity="ShopBundle\Entity\File", mappedBy="product",  cascade={"persist", "remove"})
     */
    private $pictures;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pictures = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
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
     * Add picture
     *
     * @param \ShopBundle\Entity\File $picture
     *
     * @return Product
     */
    public function addPicture(\ShopBundle\Entity\File $picture)
    {
        $this->pictures[] = $picture;
        $picture->setProduct($this);

        return $this;
    }

    /**
     * Remove picture
     *
     * @param \ShopBundle\Entity\File $picture
     */
    public function removePicture(\ShopBundle\Entity\File $picture)
    {
        $this->pictures->removeElement($picture);
    }

    /**
     * Get pictures
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPictures()
    {
        return $this->pictures;
    }
}
