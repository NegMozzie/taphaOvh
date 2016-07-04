<?php
//src/Acme/DemoBundle/Entity/Taxonomy.php

namespace Msports\BlogBundle\Entity; 

use ED\BlogBundle\Interfaces\Model\BlogTaxonomyInterface;
use ED\BlogBundle\Model\Entity\Taxonomy as BaseTaxonomy;
use Msports\EventBundle\Entity\Team;
use Msports\EventBundle\Entity\Championship;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="taxonomy")
 * @ORM\Entity(repositoryClass="ED\BlogBundle\Model\Repository\TaxonomyRepository")
 */
class Category extends BaseTaxonomy implements BlogTaxonomyInterface
{

	/**
     * @ORM\ManyToMany(targetEntity="Msports\EventBundle\Entity\Team", mappedBy="categories")
     *
     */
    protected $teams;

    /**
     * @ORM\OneToMany(targetEntity="Msports\EventBundle\Entity\Championship", mappedBy="category")
     */
    protected $events;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="excerpt_photo_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $excerptPhoto;

    function __construct()
    {
    	parent::__construct();
        $this->events = new ArrayCollection();
        $this->teams = new ArrayCollection();
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

    /**
     * @return mixed
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * @param mixed $articles
     */
    public function setTeams($articles)
    {
        $this->teams = $articles;
        return $this;
    }

}