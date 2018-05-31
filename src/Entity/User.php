<?php

namespace App\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
	
	/**
	* @ORM\OneToOne(targetEntity="App\Entity\UserExtra", mappedBy="user", cascade={"persist"})
	*/
	private $extra;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExtra(): ?UserExtra
    {
        return $this->extra;
    }

    public function setExtra(?UserExtra $extra): self
    {
        $this->extra = $extra;

        // set (or unset) the owning side of the relation if necessary
        $newUser = $extra === null ? null : $this;
        if ($newUser !== $extra->getUser()) {
            $extra->setUser($newUser);
        }

        return $this;
    }
}
