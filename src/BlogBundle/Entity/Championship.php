<?php


namespace BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use BlogBundle\Model\Event;
use BlogBundle\Entity\GrandPrix;
use BlogBundle\Entity\Season;
use BlogBundle\Entity\Classement;
use Doctrine\ORM\Mapping as ORM;

/**
 * 
 * @ORM\Table(name="championnat")
 * @ORM\Entity(repositoryClass="BlogBundle\Entity\Repository\ChampionshipRepository")
 */
class Championship extends Event
{
	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="BlogBundle\Entity\Season", inversedBy="events")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="BlogBundle\Entity\GrandPrix", mappedBy="parent")
     */
    protected $children;

    /**
     * @ORM\OneToMany(targetEntity="BlogBundle\Entity\Classement", mappedBy="champ", cascade={"persist", "remove"})
     */
    protected $classements;

    /**
     * @ORM\ManyToMany(targetEntity="BlogBundle\Entity\Team", inversedBy="events")
     * @ORM\JoinTable(name="event_team_relation",
     *      joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="team_id", referencedColumnName="id")})
     *
     */
    protected $teams;

     /**
     * @ORM\ManyToOne(targetEntity="BlogBundle\Entity\Taxonomy", inversedBy="events")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    protected $category;

    /**
     * @ORM\ManyToMany(targetEntity="BlogBundle\Entity\Comment")
     * @ORM\JoinTable(name="champ_comment_relation",
     *      joinColumns={@ORM\JoinColumn(name="champ_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="comment_id", referencedColumnName="id")})
     *
     */
    protected $comments;

    /**
     * Class constructor
     */
    public function __construct()
    {
    	parent::__construct();
        $this->teams = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->children = new ArrayCollection();
        $this->classements = new ArrayCollection();
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
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent(Season $parent=null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren()
    {
        $array = $this->children->getIterator();
        $array->uasort(function($a, $b)
        {
            if ($a->getStartsAt() == $b->getStartsAt())
            {
                return 0;
            }
            return ($a->getStartsAt() < $b->getStartsAt()) ? -1 : 1;
        });
        return $array;
    }

    /**
     * {@inheritdoc}
     */
    public function setChildren($children)
    {
        $this->children = new ArrayCollection();

        foreach ($children as $category) {
            $this->addChild($category);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeChild(Grandprix $childToDelete)
    {
        foreach ($this->getChildren() as $pos => $child) {
            if ($childToDelete->getId() && $child->getId() === $childToDelete->getId()) {
                unset($this->children[$pos]);

                return;
            }

            if (!$childToDelete->getId() && $child === $childToDelete) {
                unset($this->children[$pos]);

                return;
            }
        }
    }

    public function addChild(Grandprix $child)
    {
        if(!$this->children->contains($child))
        {
            $child->setParent($this);
            $this->children->add($child);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasChildren()
    {
        return count($this->children) > 0;
    }

    /**
     * @return mixed
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * @return mixed
     */

    /**
     * @return mixed
     */
    public function getClassements()
    {
        return $this->classements;
    }

    /**
     * @param mixed $comments
     */
    public function setClassements($classements)
    {
        $this->classements = $classements;

        return $this;
    }

    public function addClassement(Classement $classement)
    {
        if(!$this->classements->contains($classement))
        {
            $classement->setChamp($this);
            $this->classements->add($classement);
        }
    }

    public function removeClassement(Classement $classement)
    {
        if($this->classements->contains($classement))
        {
            $classement->setChamp(null);
            $this->classements->removeElement($classement);
        }
    }


    public function getTeamsClass()
    {
        $pc = array();
        $teams = $this->category->getTeams();
        foreach ($teams as $team)
        {
            $temp = new Classement();
            $temp->setPoints(0);
            $temp->setRank(0);
            foreach ($team->getPilots() as $pilot)
            {
                $fullname = $pilot->getFullName();
                $class = $this->getPilotClass($fullname);
                if ($class)
                {
                    $temp->setPoints($class->getPoints() + $temp->getPoints());
                    $temp->setPilot($pilot);
                }
            }
            $pc[] = $temp;
        }
        uasort($pc, function($a, $b)
        {
            if ($a->getPoints() == $b->getPoints())
            {
                return 0;
            }
            return ($a->getPoints() < $b->getPoints()) ? 1 : -1;
        });
        return $pc;
    }

    public function getPilots()
    {
        $tc = array();
        $teams = $this->category->getTeams();
        foreach ($teams as $team)
        {
            foreach ($team->getPilots() as $pilot)
            {

                $fullname = $pilot->getFullName();
                $class = $this->getPilotClass($fullname);
                if ($class)
                    $tc [$class->getRank()] = $class;
            }
        }
        ksort($tc);
        return $tc;
    }

    public function getPilotClass($fullname)
    {
        $c = null;
        foreach ($this->classements as $cl) {
            if ($cl->getPilot()->getFullName() == $fullname) {
                return $cl;
            }
        }
        return $c;
    }


    /**
     * @param mixed $teams
     */
    public function setTeams($teams)
    {
        $this->teams = $teams;
        return $this;
    }
    public function addTeam(Team $team)
    {
        if(!$this->teams->contains($team))
        {
            $team->addEvent($this);
            $this->teams->add($team);
        }
    }
    public function removeTeam(Team $team)
    {
        if($this->teams->contains($team))
        {
            $team->setEvent(null);
            $this->teams->removeElement($team);
        }
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory(Taxonomy $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    public function addComment(Comment $comment)
    {
        if(!$this->comments->contains($comment))
        {
            $comment->setArticle($this);
            $this->comments->add($comment);
        }
    }

    public function removeComment(Comment $comment)
    {
        if($this->comments->contains($comment))
        {
            $comment->setArticle(null);
            $this->comments->removeElement($comment);
        }
    }

    /**
     * (Add this method into your class)
     *
     * @return string String representation of this class
     */
    public function __toString()
    {
        return $this->name.' '.$this->parent;
    }
}