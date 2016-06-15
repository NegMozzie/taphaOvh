<?php


namespace BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use BlogBundle\Model\Event;
use BlogBundle\Entity\GrandPrix;
use BlogBundle\Entity\Comment;
use BlogBundle\Entity\Classement;
use Doctrine\ORM\Mapping as ORM;

/** 
 * @ORM\Table(name="course")
 * @ORM\Entity(repositoryClass="BlogBundle\Entity\Repository\CourseRepository")
 */
class Course extends Event
{
    const TYPE_COURSE = "course";
    const TYPE_ESSAI = "essai";

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="BlogBundle\Entity\Article")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id", nullable=true)
     */
    protected $article;

    /**
     * @ORM\ManyToOne(targetEntity="BlogBundle\Entity\GrandPrix", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    
    protected $parent;
    
    /**
     * @ORM\OneToMany(targetEntity="BlogBundle\Entity\Classement", mappedBy="course", cascade={"persist", "remove"})
     */
    protected $classements;

    /**
     * @ORM\ManyToMany(targetEntity="BlogBundle\Entity\Comment")
     * @ORM\JoinTable(name="course_comment_relation",
     *      joinColumns={@ORM\JoinColumn(name="course_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="comment_id", referencedColumnName="id")})
     *
     */
    protected $comments;

    public function __construct()
    {
        parent::__construct();
        $this->comments = new ArrayCollection();
        $this->classements = new ArrayCollection();
    }

    public function getPilots()
    {
        $pilots = array();
        $teams = $this->parent->getParent()->getCategory()->getTeams();
        foreach ($teams as $team) {
            foreach ($team->getPilots() as $pilot) {

                $fullname = $pilot->getFullName();
                $class = $this->getPilotClass($fullname);
                if ($class)
                    $pilots [$class->getRank()] = $class;
            }
        }
        ksort($pilots);
        return $pilots;
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

    public function addClassement(Classement $classement)
    {
        if(!$this->classements->contains($classement))
        {
            $classement->setCourse($this);
            $this->classements->add($classement);
        }
    }

    public function removeClassement(Classement $classement)
    {
        if($this->classemnets->contains($classement))
        {
            $classement->setCourse(null);
            $this->classements->removeElement($classement);
        }
    }

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
    public function setClassements($comments)
    {
        $this->classements = $comments;

        return $this;
    }

    /**
     * @return mixed
     */

    public function getType()
    {
        $type = strtolower($this->name);
        $pos = strpos($type, Course::TYPE_ESSAI);
        if ($pos !== false) {
            return Course::TYPE_ESSAI;
        } else {
            return Course::TYPE_COURSE;
        }
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
    public function setParent(Grandprix $parent=null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param mixed $article
     */
    public function setArticle(Article $article = null)
    {
        $this->article = $article;
        return $this;
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
            $this->comments->add($comment);
        }
    }

    public function removeComment(Comment $comment)
    {
        if($this->comments->contains($comment))
        {
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

    public function getDay($month)
    {


        switch($month)
        {
            case "1" :
                $stringmonth = "Lundi";
                break;
            case "2" :
                $stringmonth = "Mardi";
                break;
            case "3" :
                $stringmonth = "Mercredi";
                break;
            case "4" :
                $stringmonth = "Jeudi";
                break;
            case "5" :
                $stringmonth = "Vendredi";
                break;
            case "6" :
                $stringmonth = "Samedi";
                break;
            case "7" :
                $stringmonth = "Dimanche";
                break;
            case 'Monday': 
                $stringmonth = 'Lundi'; 
                break;
            case 'Tuesday': $stringmonth = 'Mardi';
                break;
            case 'Wednesday': $stringmonth = 'Mercredi';
                break;
            case 'Thursday': $stringmonth = 'Jeudi';
                break;
            case 'Friday': $stringmonth = 'Vendredi';
                break;
            case 'Saturday': $stringmonth = 'Samedi';
                break;
            case 'Sunday': $stringmonth = 'Dimanche';
                break;
        
        }

        return $stringmonth;
    }

}