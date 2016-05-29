<?php
/**
 * Creat by Eton Digital.
 * User: Vladimir Mladenovic (vladimir.mladenovic@etondigital.com)
 * Date: 12.5.15.
 * Time: 10.45
 */

namespace BlogBundle\Controller\Frontend;

use BlogBundle\Handler\Pagination;
use BlogBundle\Entity\Taxonomy;
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
        $taxonomyType = Taxonomy::TYPE_CATEGORY;
        $taxonomy = $this->get('app_repository_taxonomy')->findBySlug($categorySlug);

        if(!($taxonomy && $taxonomy->getType()==$taxonomyType))
        {
            throw new NotFoundHttpException("Category not found.");
        }

        $criteria['type'] = $taxonomyType;
        $criteria['value'] = $taxonomy;

        $paginator = $this->get('_blog.paginator');
        $response = $paginator->paginate(
            $this->get('app_repository_championship')->findByTaxonomy($categorySlug,$taxonomyType),
            'BlogBundle:Frontend/Blog:calendrier',
            'BlogBundle:Frontend/Global:pagination',
            array("criteria" => $criteria),
            100,
            null,
            $paginationTemplate = 'BlogBundle:Frontend/Global:pagination.html.twig',
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
        $taxonomyType = Taxonomy::TYPE_CATEGORY;
        $taxonomy = $this->get('app_repository_taxonomy')->findBySlug($categorySlug);

        if(!($taxonomy && $taxonomy->getType()==$taxonomyType))
        {
            throw new NotFoundHttpException("Category not found.");
        }

        $criteria['type'] = $taxonomyType;
        $criteria['value'] = $taxonomy;

        $paginator = $this->get('_blog.paginator');
        $response = $paginator->paginate(
            $this->get('app_repository_championship')->findByTaxonomy($categorySlug,$taxonomyType),
            'BlogBundle:Frontend/Blog:classement',
            'BlogBundle:Frontend/Global:pagination',
            array("criteria" => $criteria),
            100,
            null,
            $paginationTemplate = 'BlogBundle:Frontend/Global:pagination.html.twig',
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
        $taxonomyType = Taxonomy::TYPE_CATEGORY;
        $taxonomy = $this->get('app_repository_taxonomy')->findBySlug($categorySlug);

        if(!($taxonomy && $taxonomy->getType()==$taxonomyType))
        {
            throw new NotFoundHttpException("Category not found.");
        }

        $criteria['type'] = $taxonomyType;
        $criteria['value'] = $taxonomy;

        $paginator = $this->get('_blog.paginator');
        $response = $paginator->paginate(
            $this->get('app_repository_championship')->findByTaxonomy($categorySlug,$taxonomyType),
            'BlogBundle:Frontend/Blog:resultat',
            'BlogBundle:Frontend/Global:pagination',
            array("criteria" => $criteria),
            100,
            null,
            $paginationTemplate = 'BlogBundle:Frontend/Global:pagination.html.twig',
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
        $taxonomyType = Taxonomy::TYPE_CATEGORY;
        $taxonomy = $this->get('app_repository_taxonomy')->findBySlug($categorySlug);

        if(!($taxonomy && $taxonomy->getType()==$taxonomyType))
        {
            throw new NotFoundHttpException("Category not found.");
        }

        $criteria['type'] = $taxonomyType;
        $criteria['value'] = $taxonomy;

        $paginat = array();
        $paginat[] = $this->get('app_repository_grandprix')->findByName($eventName);

        return $this->render("BlogBundle:Frontend/Blog:result.html.twig", array(
                'pagination' => $paginat
                ));
        $paginator = $this->get('_blog.paginator');
        $response = $paginator->paginate(
            $this->get('app_repository_grandprix')->findByName($eventName),
            'BlogBundle:Frontend/Blog:result',
            'BlogBundle:Frontend/Global:pagination',
            array("criteria" => $criteria),
            100,
            null,
            $paginationTemplate = 'BlogBundle:Frontend/Global:pagination.html.twig',
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
        $taxonomyType = Taxonomy::TYPE_CATEGORY;
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
        return $this->render("BlogBundle:Frontend/Blog:class.html.twig", array(
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
        return $this->render("BlogBundle:Frontend/Blog:grandprix.html.twig",
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
            $template = "BlogBundle:Frontend/Blog:essai.html.twig";
        else if (strstr($eventName, "course")) {
            $template = "BlogBundle:Frontend/Blog:grandprix.html.twig";
            $course = $event;
            $event = $event->getParent();
        }
        else if (strstr($eventName, "qualification"))
            $template = "BlogBundle:Frontend/Blog:course.html.twig";
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


