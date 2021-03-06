<?php
/**
 * Creat by Eton Digital.
 * User: Vladimir Mladenovic (vladimir.mladenovic@etondigital.com)
 * Date: 12.5.15.
 * Time: 10.45
 */

namespace Msports\EventBundle\Controller\Frontend;

use ED\BlogBundle\Handler\Pagination;
use Msports\BlogBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventController extends Controller
{
    /**
     * @Route("/category/{categorySlug}/events", name="ed_blog_events")
     * @Route("/category/{categorySlug}/events/", name="ed_blog_frontend_events")
     */
    public function indexAction($categorySlug)
    {
        $taxonomyType = Category::TYPE_CATEGORY;
        $taxonomy = $this->get('app_repository_taxonomy')->findBySlug($categorySlug);

        if(!($taxonomy && $taxonomy->getType()==$taxonomyType))
        {
            throw new NotFoundHttpException("Category not found.");
        }

        $criteria['type'] = $taxonomyType;
        $criteria['value'] = $taxonomy;

        $paginator = $this->get('ed_blog.paginator');
        $response = $paginator->paginate(
            $this->get('app_repository_championship')->findByTaxonomy($categorySlug,$taxonomyType),
            'MsportsEventBundle:Frontend/Blog:calendrier',
            'EDBlogBundle:Frontend/Global:pagination',
            array("criteria" => $criteria),
            100,
            null,
            $paginationTemplate = 'EDBlogBundle:Frontend/Global:pagination.html.twig',
            array(),
            null
        );

        return $response;
    }

    /**
     * @Route("/category/{categorySlug}/classement", name="ed_blog_classements")
     * @Route("/category/{categorySlug}/classement/", name="ed_blog_frontend_classements")
     */
    public function classementAction($categorySlug)
    {
        $taxonomyType = Category::TYPE_CATEGORY;
        $taxonomy = $this->get('app_repository_taxonomy')->findBySlug($categorySlug);

        if(!($taxonomy && $taxonomy->getType()==$taxonomyType))
        {
            throw new NotFoundHttpException("Category not found.");
        }

        $criteria['type'] = $taxonomyType;
        $criteria['value'] = $taxonomy;

        $paginator = $this->get('ed_blog.paginator');
        $response = $paginator->paginate(
            $this->get('app_repository_championship')->findByTaxonomy($categorySlug,$taxonomyType),
            'MsportsEventBundle:Frontend/Blog:classement',
            'EDBlogBundle:Frontend/Global:pagination',
            array("criteria" => $criteria),
            100,
            null,
            $paginationTemplate = 'EDBlogBundle:Frontend/Global:pagination.html.twig',
            array(),
            null
        );

        return $response;
    }


     /**
     * @Route("/category/{categorySlug}/resultats", name="ed_blog_resultats")
     * @Route("/category/{categorySlug}/resultats/", name="ed_blog_frontend_resultats")
     */
    public function resultatAction($categorySlug)
    {
        $taxonomyType = Category::TYPE_CATEGORY;
        $taxonomy = $this->get('app_repository_taxonomy')->findBySlug($categorySlug);

        if(!($taxonomy && $taxonomy->getType()==$taxonomyType))
        {
            throw new NotFoundHttpException("Category not found.");
        }

        $criteria['type'] = $taxonomyType;
        $criteria['value'] = $taxonomy;

        $paginator = $this->get('ed_blog.paginator');
        $response = $paginator->paginate(
            $this->get('app_repository_championship')->findByTaxonomy($categorySlug,$taxonomyType),
            'MsportsEventBundle:Frontend/Blog:resultat',
            'EDBlogBundle:Frontend/Global:pagination',
            array("criteria" => $criteria),
            100,
            null,
            $paginationTemplate = 'EDBlogBundle:Frontend/Global:pagination.html.twig',
            array(),
            null
        );

        return $response;
    }

    /**
     * @Route("/category/{categorySlug}/resultats/{eventName}", name="ed_blog_resultat")
     * @Route("/category/{categorySlug}/resultats/{eventName}/", name="ed_blog_frontend_resultat")
     */
    public function resultAction($categorySlug, $eventName)
    {
        $taxonomyType = Category::TYPE_CATEGORY;
        $taxonomy = $this->get('app_repository_taxonomy')->findBySlug($categorySlug);

        if(!($taxonomy && $taxonomy->getType()==$taxonomyType))
        {
            throw new NotFoundHttpException("Category not found.");
        }

        $criteria['type'] = $taxonomyType;
        $criteria['value'] = $taxonomy;

        $paginat = array();
        $paginat[] = $this->get('app_repository_grandprix')->findByName($eventName);

        return $this->render("MsportsEventBundle:Frontend/Blog:result.html.twig", array(
                'pagination' => $paginat
                ));
        $paginator = $this->get('ed_blog.paginator');
        $response = $paginator->paginate(
            $this->get('app_repository_grandprix')->findByName($eventName),
            'MsportsEventBundle:Frontend/Blog:result',
            'EDBlogBundle:Frontend/Global:pagination',
            array("criteria" => $criteria),
            100,
            null,
            $paginationTemplate = 'EDBlogBundle:Frontend/Global:pagination.html.twig',
            array(),
            null
        );

        return $response;
    }

    /**
     * @Route("/category/{categorySlug}/classement/{eventName}", name="ed_blog_classement")
     * @Route("/category/{categorySlug}/classement/{eventName}/", name="ed_blog_frontend_classement")
     */
    public function classAction($categorySlug, $eventName)
    {
        $taxonomyType = Category::TYPE_CATEGORY;
        $taxonomy = $this->get('app_repository_taxonomy')->findBySlug($categorySlug);

        if(!($taxonomy && $taxonomy->getType()==$taxonomyType))
        {
            throw new NotFoundHttpException("Category not found.");
        }

        $criteria['type'] = $taxonomyType;
        $criteria['value'] = $taxonomy;

        $event = $this->get('app_repository_championship')->findByTaxonomy($categorySlug,$taxonomyType);
        foreach ($event as $child) {
            if ($child->getName() == $eventName)
                $event = $child;
        }
        return $this->render("MsportsEventBundle:Frontend/Blog:class.html.twig", array(
                'event' => $event
                ));
    }


    /**
     * @Route("/category/grandprix/{eventName}", name="ed_blog_gp")
     * @Route("/category/grandprix/{eventName}/", name="frontend_blog_gp")
     */
    public function singleGrandprixAction($eventName)
    {
        $event = $this->get('app_repository_grandprix')->findByName($eventName);

        if(!($event))
        {
            throw new NotFoundHttpException("GP not found.");
        }
        $course = null; 
        foreach ($event->getChildren() as $child) {
            if (strstr(strtolower($child->getName()), "course"))
                $course = $child;
            # code...
        }
        return $this->render("MsportsEventBundle:Frontend/Blog:grandprix.html.twig",
            array(
                'event' => $event,
                'type' => 'grandprix',
                'course' => $course
                ));
    }

    /**
     * @Route("/category/{type}/{parent}/{eventName}.transform({slug})", name="ed_blog_course")
     * @Route("/category/{type}/{parent}/{eventName}.transform({slug})", name="frontend_blog_course")
     */
    public function singleCourseAction($eventName, $parent, $slug, $type)
    {
        $event = $this->get('app_repository_course')->findByName($eventName, $slug);

        if(!($event))
        {
            throw new NotFoundHttpException("Course not found.");
        }

        $eventName = strtolower($eventName);
        $course = null;
        if (strstr($eventName, "essai"))
            $template = 'MsportsEventBundle:Frontend/Blog:essai.html.twig';
        else if (strstr($eventName, "course")) {
            $template = "MsportsEventBundle:Frontend/Blog:grandprix.html.twig";
            $course = $event;
            $event = $event->getParent();
        }
        else if (strstr($eventName, "qualification"))
            $template = 'MsportsEventBundle:Frontend/Blog:course.html.twig';
        else {
            throw new NotFoundHttpException("Evenement inconnu");
        }
        return $this->render($template,
            array(
                'event' => $event,
                'type' => '$type',
                'course' => $course
                ));
    }

}


