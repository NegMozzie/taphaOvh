<?php


namespace BlogBundle\Entity;

use BlogBundle\Entity\Championship;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="team")
 * @ORM\Entity(repositoryClass="BlogBundle\Entity\Repository\TeamRepository")
 */

class Team
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column()
     * @Assert\NotBlank()
     */
    protected $name;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;
    /**
     * @ORM\OneToMany(targetEntity="BlogBundle\Entity\Pilot", mappedBy="team")
     */
    protected $pilots;
    
    /**
     * @ORM\ManyToMany(targetEntity="BlogBundle\Entity\Championship",mappedBy="teams")
     * @ORM\JoinTable(name="team_event_relation",
     *      joinColumns={@ORM\JoinColumn(name="team_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")})
     *
     */
    protected $events;
    
    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="excerpt_photo_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $excerptPhoto;

    /**
     * @ORM\ManyToMany(targetEntity="BlogBundle\Entity\Taxonomy", inversedBy="teams")
     * @ORM\JoinTable(name="team_category_relation",
     *      joinColumns={@ORM\JoinColumn(name="team_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")})
     *
     */
    protected $categories;

    public function __construct()
    {
        $this->pilots = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }
    /**
     * @return mixed
     */
    public function getExcerptPhoto()
    {
        return $this->excerptPhoto;
    }
    /**
     * @param mixed $excerptPhoto
     */
    public function setExcerptPhoto($excerptPhoto)
    {
        $this->excerptPhoto = $excerptPhoto;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getPilots()
    {
        return $this->pilots;
    }
    /**
     * @param mixed $pilots
     */
    public function setPilots($pilots)
    {
        $this->pilots = $pilots;
        return $this;
    }
    public function addPilot(Pilot $pilot)
    {
        if(!$this->pilots->contains($pilot))
        {
            $pilot->setTeam($this);
            $this->pilots->add($pilot);
        }
    }
    public function removePilot(Pilot $pilot)
    {
        if($this->pilots->contains($pilot))
        {
            $pilot->setTeam(null);
            $this->pilots->removeElement($pilot);
        }
    }
    public function addEvent(Championship $event) {
        $event->addTeam($this);
 
        // Si l'objet fait déjà partie de la collection on ne l'ajoute pas
        if (!$this->events->contains($classement)) {
            $this->events->add($classement);
        }
    }
    public function removeEvent(Championship $event)
    {
        if($this->events->contains($event))
        {
            $this->events->removeElement($event);
        }
    }

     /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }
    /**
     * @param mixed $event
     */
    public function setCategories($event=null)
    {
        $this->categories = $event;
        return $this;
    }
    

    /**
     * @return mixed
     */
    public function getEvents()
    {
        return $this->events;
    }
    /**
     * @param mixed $event
     */
    public function setEvents($event=null)
    {
        $this->event = $event;
        return $this;
    }
    
    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setName($name)
    {
        $this->name = $name;
    }
    public function setDescription($description)
    {
        $this->description = $description;
    }
    public function __toString()
    {
        return $this->getName() ?: 'n/a';
    }
}