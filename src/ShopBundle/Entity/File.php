<?php
namespace ShopBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="ShopBundle\Repository\FileRepository")
 * @Gedmo\Uploadable(path="/uploads", filenameGenerator="SHA1", allowOverwrite=true, appendNumber=true)
 */
class File
{
    use TimestampableEntity;

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="path", type="string")
     * @Gedmo\UploadableFilePath
     */
    private $path;

    /**
     * @ORM\Column(name="name", type="string")
     * @Gedmo\UploadableFileName
     */
    private $name;

    /**
     * @ORM\Column(name="mime_type", type="string")
     * @Gedmo\UploadableFileMimeType
     */
    private $mimeType;

    /**
     * @ORM\Column(name="size", type="decimal")
     * @Gedmo\UploadableFileSize
     */
    private $size;
    
    /**
    * @ORM\ManyToOne(targetEntity="ShopBundle\Entity\Product", inversedBy="pictures")
    * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
    */
    private $product;

    /**
     * @var integer
     * @ORM\Column(name="product_id", type="integer")
     *
     * @Assert\NotBlank()
     */
    private $product_id;

    /**
     *
     * @ORM\OneToMany(targetEntity="ShopBundle\Entity\Caster", mappedBy="pictures")
     */
    private $caster;


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
     * Set path
     *
     * @param string $path
     *
     * @return File
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return File
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
     * Set mimeType
     *
     * @param string $mimeType
     *
     * @return File
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set size
     *
     * @param string $size
     *
     * @return File
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set product
     *
     * @param \ShopBundle\Entity\Product $product
     *
     * @return File
     */
    public function setProduct(\ShopBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \ShopBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set productId
     *
     * @param integer $productId
     *
     * @return File
     */
    public function setProductId($productId)
    {
        $this->product_id = $productId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return integer
     */
    public function getProductId()
    {
        return $this->product_id;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->caster = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add caster
     *
     * @param \ShopBundle\Entity\Caster $caster
     *
     * @return File
     */
    public function addCaster(\ShopBundle\Entity\Caster $caster)
    {
        $this->caster[] = $caster;

        return $this;
    }

    /**
     * Remove caster
     *
     * @param \ShopBundle\Entity\Caster $caster
     */
    public function removeCaster(\ShopBundle\Entity\Caster $caster)
    {
        $this->caster->removeElement($caster);
    }

    /**
     * Get caster
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCaster()
    {
        return $this->caster;
    }
}
