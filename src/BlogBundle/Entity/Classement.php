<?php


namespace BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Eko\FeedBundle\Item\Writer\RoutedItem;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use BlogBundle\Entity\Pilot;
use BlogBundle\Entity\Championship;
use BlogBundle\Entity\Course;

/**
 * Article
 *
 * @ORM\Table(name="classement")
 * @ORM\Entity(repositoryClass="BlogBundle\Entity\Repository\ClassementRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Classement
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * 
     */
    protected $name;
    

    /**
     * Articles in the taxonomy
     *
     * @ORM\Column(type="bigint", nullable=true, options={"default" = 0})
     */
    protected $points;

    /**
     * Articles in the taxonomy
     *
     * @ORM\Column(type="integer", nullable=true, options={"default" = 0})
     */
    protected $rank;

     /**
     * Articles in the taxonomy
     *
     * @ORM\Column(type="integer", nullable=true, options={"default" = 0})
     */
    protected $tours;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $time;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $time1;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $time2;

     /**
     * @ORM\ManyToOne(targetEntity="BlogBundle\Entity\Pilot")
     * @ORM\JoinColumn(name="pilot_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    protected $pilot;

    /**
     * @ORM\ManyToOne(targetEntity="BlogBundle\Entity\Championship", inversedBy="classements")
     * @ORM\JoinColumn(name="champ_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    protected $champ;

    /**
     * @ORM\ManyToOne(targetEntity="BlogBundle\Entity\Course", inversedBy="classements")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    protected $course;


    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * @return mixed
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param mixed $points
     */
    public function setPoints($points)
    {
        $this->points = $points;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTours()
    {
        return $this->tours;
    }

    /**
     * @param mixed $points
     */
    public function setTours($points)
    {
        $this->tours = $points;
        return $this;
    }

    /**
     * @param mixed $points
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRank()
    {
        return $this->rank;
    }

    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime(\DateTime $time = null)
    {
        $this->time = $time;

        return $this;
    }

    public function getTime1()
    {
        return $this->time1;
    }

    /**
     * @param mixed $time
     */
    public function setTime1(\DateTime $time = null)
    {
        $this->time1 = $time;

        return $this;
    }

    public function getTime2()
    {
        return $this->time2;
    }

    /**
     * @param mixed $time
     */
    public function setTime2(\DateTime $time = null)
    {
        $this->time2 = $time;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
    * @ORM\PreUpdate
    * @ORM\PrePersist
    */
    public function updateEntity()
    {
        $nam = "Classement ";
        $nam = $nam.$this->getPilot().' ';
        if ($this->getCourse())
            $nam = $nam.$this->getCourse();
        else
            $nam = $nam.$this->getChamp();
        $this->name = $nam;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPilot()
    {
        return $this->pilot;
    }

    /**
     * @param mixed $parent
     */
    public function setPilot(Pilot $pilot=null)
    {
        $this->pilot = $pilot;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getChamp()
    {
        return $this->champ;
    }

    /**
     * @param mixed $parent
     */
    public function setChamp(Championship $pilot=null)
    {
        $this->champ = $pilot;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * @param mixed $parent
     */
    public function setCourse(Course $pilot=null)
    {
        $this->course = $pilot;

        return $this;
    }

    public function __toString()
    {
        return $this->getName()."";
    }

}
