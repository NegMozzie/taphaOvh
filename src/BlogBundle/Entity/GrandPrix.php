<?php


namespace BlogBundle\Entity;

use BlogBundle\Model\Event;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Eko\FeedBundle\Item\Writer\RoutedItem;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * GrandPrix
 *
 * @ORM\Table(name="grandprix")
 * @ORM\Entity(repositoryClass="BlogBundle\Entity\Repository\GrandPrixRepository")
 */
class GrandPrix extends Event
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="BlogBundle\Entity\Championship", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="BlogBundle\Entity\Course", mappedBy="parent")
     */
    protected $children;

    /**
     * @ORM\ManyToMany(targetEntity="BlogBundle\Entity\Comment")
     * @ORM\JoinTable(name="grandprix_comment_relation",
     *      joinColumns={@ORM\JoinColumn(name="grandprix_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="comment_id", referencedColumnName="id")})
     *
     */

    protected $comments;
    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="excerpt_photo_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $countryPhoto;
    /**
     * @ORM\Column(name="town", type="string", length=255)
     */
    protected $town;
    /**
     * Class constructor
     */

    public function __construct()
    {
    	parent::__construct();
        $this->children = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     */
    public function setTown($town)
    {
        $this->town = $town;
    }
    /**
     * Get name
     *
     * @return string 
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * @return mixed
     */
    public function getCountryPhoto()
    {
        return $this->countryPhoto;
    }

    /**
     * @param mixed $excerptPhoto
     */
    public function setCountryPhoto($excerptPhoto)
    {
        $this->countryPhoto = $excerptPhoto;

        return $this;
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
    public function setParent(Championship $parent=null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren()
    {
        return $this->children;
    }

    public function getCourse()
    {
        foreach ($this->getChildren() as $child) {
            if (strstr(strtolower($child->getName()), "course"))
                return $child;
            # code...
        }
        return(null);
    }

    public function getQualif()
    {
        foreach ($this->getChildren() as $child) {
            if (strstr(strtolower($child->getName()), "qualif"))
                return $child;
            # code...
        }
        return(null);
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

    public function getPilotClass($fullName)
    {
        $c = null;
        if ($course = $this->getCourse()) {
            foreach ($course->getClassements() as $c) {
                if ($c->getPilot()->getFullName() == $fullname)
                    return $c;
            }
        }
        return $c;
    }

    /**
     * {@inheritdoc}
     */
    public function removeChild(Course $childToDelete)
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

    public function addChild(Course $child)
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
        $stringmonth= "";
        $month = date('w', $month);

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
        }

        return $stringmonth;
    }

    
}