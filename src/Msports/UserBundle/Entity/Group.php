<?php

namespace Msports\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\Group as BaseGroup;

/**
 * Group.
 *
 * @ORM\Table(name="account_group")
 * @ORM\Entity
 */
class Group extends BaseGroup
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="display", type="boolean", nullable=false)
     */
    protected $display;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->upload = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the value of display.
     *
     * @return bool
     */
    public function getDisplay()
    {
        return $this->display;
    }

    /**
     * Sets the value of display.
     *
     * @param bool $display the display
     *
     * @return self
     */
    public function setDisplay($display)
    {
        $this->display = $display;

        return $this;
    }
}
