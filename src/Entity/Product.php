<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Index;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @Table(indexes={@Index(columns={"title"}, flags={"fulltext"})})
 */
class Product
{
    const CACHE_TIMEOUT      = 3600;
    const CACHE_TIMEOUT_ETAG = 3600;


    use TimestampableEntity, SoftDeleteableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1000, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min = 1,max = 1000)
     */
    private $title;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Assert\NotBlank()
     * @Assert\GreaterThan(0)
     */
    private $price;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $salePrice = null;

    /**
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="products")
     * @Assert\Valid()
     */
    private $categories;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Manufacturer", inversedBy="products")
     * @ORM\JoinColumn(name="manufacturer_id", referencedColumnName="id")
     * @Assert\Valid()
     */
    private $manufacturers;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $manufacturer_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Material", inversedBy="products")
     * @ORM\JoinColumn(name="material_id", referencedColumnName="id")
     * @Assert\Valid()
     */
    private $material;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $material_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Provider", inversedBy="products")
     * @ORM\JoinColumn(name="provider_id", referencedColumnName="id")
     * @Assert\Valid()
     */
    private $provider;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $provider_id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="products", cascade={"persist"})
     * @Assert\Valid()
     */
    private $images;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $short;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $outOfStock = false;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     * @Assert\NotNull()
     */
    private $new = false;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     * @Assert\NotNull()
     */
    private $banner = false;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->images = new ArrayCollection();
    }




    public function __toString() : string
    {
        return $this->title;
    }

    public function getCacheKey() : string
    {
        return 'product_' . $this->id;
    }

    public function getCacheEtagKey() : string
    {
        return 'product_' . $this->id . '_etag';
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSalePrice(): ?int
    {
        return $this->salePrice;
    }

    public function setSalePrice(?int $salePrice): self
    {
        $this->salePrice = $salePrice;

        return $this;
    }

    public function getManufacturerId(): ?int
    {
        return $this->manufacturer_id;
    }

    public function setManufacturerId(?int $manufacturer_id): self
    {
        $this->manufacturer_id = $manufacturer_id;

        return $this;
    }

    public function getMaterialId(): ?int
    {
        return $this->material_id;
    }

    public function setMaterialId(?int $material_id): self
    {
        $this->material_id = $material_id;

        return $this;
    }

    public function getProviderId(): ?int
    {
        return $this->provider_id;
    }

    public function setProviderId(?int $provider_id): self
    {
        $this->provider_id = $provider_id;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getShort(): ?string
    {
        return $this->short;
    }

    public function setShort(?string $short): self
    {
        $this->short = $short;

        return $this;
    }

    public function getOutOfStock(): ?bool
    {
        return $this->outOfStock;
    }

    public function setOutOfStock(?bool $outOfStock): self
    {
        $this->outOfStock = $outOfStock;

        return $this;
    }

    public function getNew(): ?bool
    {
        return $this->new;
    }

    public function setNew(bool $new): self
    {
        $this->new = $new;

        return $this;
    }

    public function getBanner(): ?bool
    {
        return $this->banner;
    }

    public function setBanner(bool $banner): self
    {
        $this->banner = $banner;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    public function getManufacturers(): ?Manufacturer
    {
        return $this->manufacturers;
    }

    public function setManufacturers(?Manufacturer $manufacturers): self
    {
        $this->manufacturers = $manufacturers;

        return $this;
    }

    public function getMaterial(): ?Material
    {
        return $this->material;
    }

    public function setMaterial(?Material $material): self
    {
        $this->material = $material;

        return $this;
    }

    public function getProvider(): ?Provider
    {
        return $this->provider;
    }

    public function setProvider(?Provider $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProducts($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getProducts() === $this) {
                $image->setProducts(null);
            }
        }

        return $this;
    }
}
