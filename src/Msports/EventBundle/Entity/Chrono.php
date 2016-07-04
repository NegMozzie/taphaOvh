<?php

namespace Msports\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Msports\EventBundle\Entity\Classement;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Comment
 *
 * @ORM\Table(name="chrono")
 * @ORM\Entity(repositoryClass="Msports\EventBundle\Entity\Repository\ChronoRepository")
 */
class Chrono
{
	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *
     * @ORM\Column(type="integer", nullable=true, options={"default" = 0})
     */
    protected $hours;

    /**
     *
     * @ORM\Column(type="integer", nullable=true, options={"default" = 0})
     */
    protected $minutes;

    /**
     *
     * @ORM\Column(type="integer", nullable=true, options={"default" = 0})
     */
    protected $secondes;

    /**
     *
     * @ORM\Column(type="integer", nullable=true, options={"default" = 0})
     */
    protected $tierces;

    /**
     * @ORM\ManyToOne(targetEntity="Msports\EventBundle\Entity\Classement", inversedBy="times")
     * @ORM\JoinColumn(name="class_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    protected $classement;

    

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
     * @return mixed
     */
    public function getHours()
    {
        return $this->hours;
    }

     /**
     * Get id
     *
     * @return integer
     */
    public function getMinutes()
    {
        return $this->minutes;
    }

     /**
     * Get id
     *
     * @return integer
     */
    public function getSecondes()
    {
        return $this->secondes;
    }


    /**
     * @return mixed
     */
    public function getTierces()
    {
        return $this->tierces;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * @return mixed
     */
    public function setHours($terces)
    {
        $this->hours = $terces;
    }

     /**
     * Get id
     *
     * @return integer
     */
    public function setMinutes($terces)
    {
        $this->minutes = $terces;
    }

     /**
     * Get id
     *
     * @return integer
     */
    public function setSecondes($terces)
    {
        $this->secondes = $terces;
    }


    /**
     * @return mixed
     */
    public function setTierces($terces)
    {
        $this->tierces = $terces;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function setClassement(Classement $classement)
    {
        $this->classement = $classement;
    }


    /**
     * @return mixed
     */
    public function getClassement()
    {
        return $this->classement;
    }

    public function __toString()
    {
        return "Chrono_".$this->getClassement();
    }
}