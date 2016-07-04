<?php

namespace Msports\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;


abstract class User extends BaseUser
{
    /**
     * @var string
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    protected $created;

    /**
     * @var string
     *
     * @ORM\Column(name="activated", type="boolean")
     */
    protected $activated;

    public function __construct()
    {
        parent::__construct();
        $this->created = new \DateTime();
        $this->activated = false;
        $this->enabled = true;
    }

    public function getWSSEToken()
    {
        $nonce = hash_hmac('sha512', uniqid(null, true), uniqid(), true);
        $created = new \DateTime('now');
        $created = $created->format(\DateTime::ISO8601);
        $digest = sha1($nonce.$created.$this->getPassword(), true);

        return sprintf(
            'UsernameToken Username="%s", PasswordDigest="%s", Nonce="%s", Created="%s"',
            $this->getUsername(),
            $digest,
            $nonce,
            $created
        );
    }

    public function __toString()
    {
        return $this->username;
    }

    public function getParent()
    {
        return 'FOSUserBundle';
    }

    /**
     * Gets the value of created.
     *
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Sets the value of created.
     *
     * @param string $created the created
     *
     * @return self
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Gets the value of activated.
     *
     * @return string
     */
    public function getActivated()
    {
        return $this->activated;
    }

    /**
     * Sets the value of activated.
     *
     * @param string $activated the activated
     *
     * @return self
     */
    public function setActivated($activated)
    {
        $this->activated = $activated;

        return $this;
    }
}
