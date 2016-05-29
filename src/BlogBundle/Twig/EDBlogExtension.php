<?php
/**
 * Created by Eton Digital.
 * User: Vladimir Mladenovic (vladimir.mladenovic@etondigital.com)
 * Date: 29.5.15.
 * Time: 13.18
 */

namespace BlogBundle\Twig;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityRepository;
use BlogBundle\Handler\BlogUserHandler;
use BlogBundle\Handler\SettingsHandler;
use BlogBundle\Entity\Article;
use BlogBundle\Entity\Taxonomy;
use BlogBundle\Entity\Comment;
use BlogBundle\Util\IDEncrypt;
use Symfony\Component\HttpFoundation\Session\Session;

class EDBlogExtension extends \Twig_Extension
{
    private $doctrine;
    private $userRepo;
    private $articleRepo;
    private $session;
    private $blogSettings;
    private $commentClass;
    private $blogUserHandler;

    public function __construct(Registry $doctrine, EntityRepository $userRepo, EntityRepository $articleRepo, Session $session, SettingsHandler $blogSettings, $commentClass, BlogUserHandler $blogUserHandler)
    {
        $this->doctrine = $doctrine;
        $this->userRepo=$userRepo;
        $this->articleRepo = $articleRepo;
        $this->session = $session;
        $this->blogSettings = $blogSettings;
        $this->commentClass = $commentClass;
        $this->blogUserHandler = $blogUserHandler;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('encrypt', array($this, 'encrypt')),
            new \Twig_SimpleFilter('showDate', array($this, 'showDate')),
            new \Twig_SimpleFilter('categoryLevelSlash', array($this, 'categoryLevelSlash')),
            new \Twig_SimpleFilter('blogTime', array($this, 'blogTime')),
            new \Twig_SimpleFilter('blogDate', array($this, 'blogDate')),
            new \Twig_SimpleFilter('blogDateTime', array($this, 'blogDateTime')),
            new \Twig_SimpleFilter('displayLinks', array($this, 'displayLinks')),
            new \Twig_SimpleFilter('blogRole', array($this, 'blogRole')),
            new \Twig_SimpleFilter('getMonth', array($this, 'getMonth')),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getMonth', array($this, 'getMonth')),
            new \Twig_SimpleFunction('categoriesFromFirstLevel', array($this, 'categoriesFromFirstLevel')),
            new \Twig_SimpleFunction('numberOfPublishedPosts', array($this, 'numberOfPublishedPosts')),
            new \Twig_SimpleFunction('getSortedOrderClass', array($this, 'getSortedOrderClass')),
            new \Twig_SimpleFunction('commentsEnabled', array($this, 'commentsEnabled')),
            new \Twig_SimpleFunction('commentsPubliclyVisible', array($this, 'commentsPubliclyVisible')),
            new \Twig_SimpleFunction('isMyComment', array($this, 'isMyComment')),
            new \Twig_SimpleFunction('commentsCount', array($this, 'commentsCount'))
        );
    }


    public function getName()
    {
        return "ed_blog_extension";
    }

    public function encrypt($id)
    {
        $return = IDEncrypt::encrypt($id);
        return $return;
    }

    public function showDate(\DateTime $date, $format = 'd.m.Y')
    {
        return $date->format($format);
    }

    public function categoryLevelSlash(Taxonomy $category)
    {
        $slash="";
        $parent = $category;

        while($parent = $parent->getParent())
        {
            $slash .= "-";
        }

        return $slash . ' ' .  $category->getTerm()->getTitle();
    }

    public function blogTime(\DateTime $date)
    {
        $format = $this->blogSettings->getSettingBlogTimeFormat();

        return $date->format($format);
    }

    public function blogDate($date)
    {
        if ($date instanceof \DateTime)
        {
            $format = $this->blogSettings->getSettingBlogDateFormat();

            $english_days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
            $french_days = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
            $english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'Décember');
            $french_months = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
        return str_replace($english_months, $french_months, str_replace($english_days, $french_days, $date->format('l j  F Y ')));

            return $date->format($format);
        }
        else
        {
            return '(not available)';
        }
    }

    public function blogDateTime(\DateTime $date)
    {
        $formatTime = $this->blogSettings->getSettingBlogTimeFormat();
        $formatDate = $this->blogSettings->getSettingBlogDateFormat();

        return $date->format("$formatDate $formatTime");
    }

    

    //Functions
    public function getMonth($month)
    {
        $stringmonth= $month;

        switch($month)
        {
            case "1" :
                $stringmonth = "Janvier";
                break;
            case "2" :
                $stringmonth = "Fevrier";
                break;
            case "3" :
                $stringmonth = "Mars";
                break;
            case "4" :
                $stringmonth = "Avril";
                break;
            case "5" :
                $stringmonth = "Mai";
                break;
            case "6" :
                $stringmonth = "Juin";
                break;
            case "7" :
                $stringmonth = "Julliet";
                break;
            case "8" :
                $stringmonth = "Aout";
                break;
            case "9" :
                $stringmonth = "Septembre";
                break;
            case "10" :
                $stringmonth = "Octobre";
                break;
            case "11" :
                $stringmonth = "Novembre";
                break;
            case "12" :
                $stringmonth = "Decembre";
                break;
        }

        return $stringmonth;
    }

    public function categoriesFromFirstLevel(Taxonomy $category)
    {

        $categories=array();
        $parent = $category;

        while($parent = $parent->getParent())
        {
            $categories[]=$parent;
        }

        return array_reverse($categories);
    }

    public function numberOfPublishedPosts($user)
    {
        $number = $this->articleRepo->getNumberOfActiveBlogs($user);

        return $number;
    }

    public function getSortedOrderClass($orderBy,$order,$thTitle)
    {
        $class="";
        if ($orderBy && $orderBy==$thTitle)
        {
            if ($order && $order=='asc')
            {
                $class="sort sort--asc";
            }else
            {
                $class="sort sort--desc";
            }
        }
        return $class;
    }

    public function commentsEnabled()
    {
        return $this->blogSettings->commentsEnabled();
    }

    public function commentsPubliclyVisible()
    {
        return $this->blogSettings->commentsPubliclyVisible();
    }

    public function isMyComment(Comment $comment)
    {
        $myComments = $this->session->get('sessionComments', false);

        if(!$myComments)
        {
            return false;
        }
        else
        {
            $myComments = unserialize($myComments);
        }

        return in_array( $comment->getId(), $myComments );
    }

    public function commentsCount(Article $article)
    {
        $result = $this->doctrine->getRepository( $this->commentClass )->findCountByArticle($article);

        return $result;
    }

    public function displayLinks($value)
    {
        $value = strip_tags($value);
        //match valid url
        $urlRegEx="/\(?(?:(http|https|ftp):\/\/)?(?:((?:[^\W\s]|\.|-|[:]{1})+)@{1})?((?:www.)?(?:[^\W\s]|\.|-)+[\.][^\W\s]{2,4}|localhost(?=\/)|\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})(?::(\d*))?([\/]?[^\s\?]*[\/]{1})*(?:\/?([^\s\n\?\[\]\{\}\#]*(?:(?=\.)){1}|[^\s\n\?\[\]\{\}\.\#]*)?([\.]{1}[^\s\?\#]*)?)?(?:\?{1}([^\s\n\#\[\]]*))?([\#][^\s\n]*)?\)?/";
        preg_match_all($urlRegEx, $value,$matches);

        if (!count($matches[0]))
        {
            return $value;
        }
        else
        {
            $result = $value;
            $protocols = array('//', 'http', 'ftp');
            $doneURLs = array();

            foreach ($matches[0] as $val)
            {
                if (!in_array($val, $doneURLs))
                {
                    $href = $val;
                    $hasProtocol = false;

                    foreach ($protocols as $prot)
                    {
                        if (strpos($href, $prot) === 0) {
                            $hasProtocol = true;
                            break;
                        }
                    }

                    if (!$hasProtocol)
                        $href = 'http://' . $href;

                    $result = str_replace($val, '<a target="_blank" href="' . $href . '">' . $val . '</a>', $result);
                    $doneURLs[] = $val;
                }
            }

            return $result;
        }
    }

    public function blogRole($user)
    {
        if(!$user)
            return "empty";

        return $this->blogUserHandler->getDefaultBlogRoleName($user);
    }
}