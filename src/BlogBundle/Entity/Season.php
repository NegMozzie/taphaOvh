<?php


namespace BlogBundle\Entity;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Eko\FeedBundle\Item\Writer\RoutedItem;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Article
 *
 * @ORM\Table(name="season")
 * @ORM\Entity(repositoryClass="BlogBundle\Entity\Repository\SeasonRepository")
 */
class Season
{
    const STATUS_PRESENT = 'en cours';
    const STATUS_PAST = 'fini';
    const STATUS_FUTURE = 'a venir';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * 
     */
    protected $startYear;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * 
     */
    protected $endYear;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $status;

    /**
     * @ORM\OneToMany(targetEntity="BlogBundle\Entity\Championship", mappedBy="parent")
     */
    protected $events;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;


    function __construct(){
        $this->events = new ArrayCollection();
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
     * @return integer
     */
    public function getStartYear()
    {
        return $this->startYear;
    }

    /**
     * @param mixed $startYear
     */
    public function setStartYear($startYear)
    {
        $this->startYear = $startYear;

        return $this;
    }


    

    /**
     * @return integer
     */
    public function getEndYear()
    {
        return $this->endYear;
    }

    /**
     * @param mixed $startYear
     */
    public function setEndYear($endYear)
    {
        $this->endYear = $endYear;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function addEvent(GrandPrix $event) {
        $event->addTeam($this);
 
        // Si l'objet fait déjà partie de la collection on ne l'ajoute pas
        if (!$this->events->contains($classement)) {
            $this->events->add($classement);
            $event->setParent($this);
        }
    }

    public function removeEvent(GrandPrix $event)
    {
        if($this->events->contains($event))
        {
            $this->events->removeElement($event);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * {@inheritdoc}
     */
    public function setEvents($event=null)
    {
        $this->events = $event;
        return $this;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    public function __toString()
    {
        return ($this->name);
    }
}
