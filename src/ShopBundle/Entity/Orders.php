<?php

namespace ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * Orders
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="ShopBundle\Repository\OrdersRepository")
 */
class Orders
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
     *
     * @ORM\ManyToOne(targetEntity="ShopBundle\Entity\Product", inversedBy="orders")
     */
    private $product;

    /**
     *
     * @ORM\ManyToOne(targetEntity="ShopBundle\Entity\Caster", inversedBy="orders")
     */
    private $caster;


    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;


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
     * Set status
     *
     * @param integer $status
     *
     * @return Orders
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set product
     *
     * @param \ShopBundle\Entity\Product $product
     *
     * @return Orders
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
     * Set caster
     *
     * @param \ShopBundle\Entity\Caster $caster
     *
     * @return Orders
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
}
