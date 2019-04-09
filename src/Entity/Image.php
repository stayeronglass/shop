<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Imagine\Image\Box;
use Imagine\Image\ManipulatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile as UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image
{
    /**
     * Unmapped property to handle file uploads
     */
    private $file;


    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="images")
    * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
    */
    private $products;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $product_id;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $ext;


    /**
     * Главное изображение. Показывается первым и на всяких баннерах
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $main = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getProductId(): ?int
    {
        return $this->product_id;
    }

    public function setProductId(?int $product_id): self
    {
        $this->product_id = $product_id;

        return $this;
    }

    public function getExt(): ?string
    {
        return $this->ext;
    }

    public function setExt(string $ext): self
    {
        $this->ext = $ext;

        return $this;
    }

    public function getMain(): ?bool
    {
        return $this->main;
    }

    public function setMain(?bool $main): self
    {
        $this->main = $main;

        return $this;
    }

    public function getProducts(): ?Product
    {
        return $this->products;
    }

    public function setProducts(?Product $products): self
    {
        $this->products = $products;

        return $this;
    }


    /**
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Lifecycle callback to upload the file to the server.
     */
    public function lifecycleFileUpload()
    {
        $this->upload();
    }

    /**
     * Updates the hash value to force the preUpdate and postUpdate events to fire.
     */
    public function refreshUpdated()
    {
        $this->setUpdatedAt(new \DateTime());
    }


    /**
     * Manages the copying of the file to the relevant place on the server
     */
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // we use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and target filename as params
        $this->getFile()->move(
            self::SERVER_PATH_TO_IMAGE_FOLDER,
            $this->getFile()->getClientOriginalName()
        );

        $filename = md5(rand());
        $dirname = 'public/upload' . DIRECTORY_SEPARATOR . $filename[0] . DIRECTORY_SEPARATOR . $filename[1];

        if(!file_exists($dirname)) mkdir($dirname, 0755, true);

        $image = $this->imagine->load($this->getFile());

        $image->save($dirname . DIRECTORY_SEPARATOR . $filename . '.jpg', [
            'jpeg_quality' => 100,
        ]);

        $image->thumbnail(new Box(450, 450), ManipulatorInterface::THUMBNAIL_OUTBOUND | ManipulatorInterface::THUMBNAIL_FLAG_UPSCALE)
            ->save($dirname . DIRECTORY_SEPARATOR . $filename . '450x450.jpg', [
                'jpeg_quality' => 100,
            ]);

        $image->thumbnail(new Box(160, 160), ManipulatorInterface::THUMBNAIL_OUTBOUND | ManipulatorInterface::THUMBNAIL_FLAG_UPSCALE)
            ->save($dirname . DIRECTORY_SEPARATOR . $filename . '160x160.jpg', [
                'jpeg_quality' => 100,
            ]);


        // set the path property to the filename where you've saved the file
        $this->setName($filename);
        $this->setExt('jpg');

        // clean up the file property as you won't need it anymore
        $this->setFile(null);
    }


    public function __toString()
    {
        return (string) $this->id;
    }

}
