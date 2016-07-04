<?php

namespace Msports\UserBundle\Entity;

use ED\BlogBundle\Interfaces\Model\BlogUserInterface;
use ED\BlogBundle\Interfaces\Model\ArticleCommenterInterface;
use Msports\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;


/**
 * Page.
 *
 * @ORM\Table(name="user_admin")
 * @ORM\Entity
 */
class Admin extends BaseUser implements BlogUserInterface, ArticleCommenterInterface
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=150, nullable=false)
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=150, nullable=false)
     */
    protected $lastname;

    /**
     * Required by BlogUserInterface
     *
     * @ORM\Column(name="blog_display_name", type="string")
     */
    protected $blogDisplayName;

    public function getBlogDisplayName()
    {
        return $this->blogDisplayName;
    }

    public function setBlogDisplayName($blogDisplayName = null)
    {
        $this->blogDisplayName = $this->firstname.' '.strtoupper($this->lastname);

        return $this;
    }

    public function getCommenterDisplayName()
    {
        return $this->blogDisplayName;
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function __toString()
    {
        return $this->firstname.' '.strtoupper($this->lastname);
    }

    public function getParent()
    {
        return 'FOSUserBundle';
    }

    /**
     * Gets the value of id.
     *
     * @return int
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Sets the value of id.
     *
     * @param int $id the id
     *
     * @return self
     */
    public function setID($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the value of firstname.
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Sets the value of firstname.
     *
     * @param string $firstname the firstname
     *
     * @return self
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Gets the value of lastname.
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Sets the value of lastname.
     *
     * @param string $lastname the lastname
     *
     * @return self
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }
}
