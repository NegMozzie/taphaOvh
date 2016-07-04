<?php


namespace Msports\EventBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Eko\FeedBundle\Item\Writer\RoutedItem;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Msports\EventBundle\Entity\Pilot;
use Msports\EventBundle\Entity\Championship;
use Msports\EventBundle\Entity\Course;
use Msports\EventBundle\Entity\Chrono;

/**
 * Article
 *
 * @ORM\Table(name="classement")
 * @ORM\Entity(repositoryClass="Msports\EventBundle\Entity\Repository\ClassementRepository")
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
     * @ORM\OneToMany(targetEntity="Msports\EventBundle\Entity\Chrono", mappedBy="classement", cascade={"persist", "remove"})
     */
    protected $times;


     /**
     * @ORM\ManyToOne(targetEntity="Msports\EventBundle\Entity\Pilot")
     * @ORM\JoinColumn(name="pilot_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    protected $pilot;

    /**
     * @ORM\ManyToOne(targetEntity="Msports\EventBundle\Entity\Championship", inversedBy="classements")
     * @ORM\JoinColumn(name="champ_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    protected $champ;

    /**
     * @ORM\ManyToOne(targetEntity="Msports\EventBundle\Entity\Course", inversedBy="classements")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    protected $course;

    public function __construct()
    {
        $this->times= new ArrayCollection();
    }


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

    /**
     * @return mixed
     */
    public function getTimes()
    {
        return $this->times;
    }

    /**
     * @param mixed $comments
     */
    public function setTimes($times)
    {
        $this->times = $times;

        return $this;
    }

    public function addTime(Chrono $time)
    {
        if(!$this->times->contains($time))
        {
            $time->setClassement($this);
            $this->times->add($time);
        }
    }

    public function removeTime(Chrono $time)
    {
        if($this->times->contains($time))
        {
            $time->setClassement(null);
            $this->times->removeElement($time);
        }
    }

}
