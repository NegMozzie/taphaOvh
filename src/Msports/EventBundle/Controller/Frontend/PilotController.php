<?php
/**
 * Creat by Eton Digital.
 * User: Vladimir Mladenovic (vladimir.mladenovic@etondigital.com)
 * Date: 12.5.15.
 * Time: 10.45
 */

namespace Msports\EventBundle\Controller\Frontend;

use ED\BlogBundle\Handler\Pagination;
use Msports\EventBundle\Entity\Pilot;
use Msports\BlogBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PilotController extends Controller
{
    /**
     * @Route("/category/{categorySlug}/pilotes", name="ed_blog_pilots")
     * @Route("/category/{categorySlug}/pilotes/", name="ed_blog_frontend_pilots")
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
            $this->get('app_repository_pilot')->findByTaxonomy($categorySlug,$taxonomyType),
            'MsportsEventBundle:Frontend/Blog:pilots',
            'EDBlogBundle:Frontend/Global:pagination',
            array("criteria" => $criteria),
            50,
            null,
            $paginationTemplate = 'MsportsBlogBundle:Frontend/Global:pagination.html.twig',
            array(),
            null
        );

        return $response;
    }


    /**
     * @Route("/blog/pilots/{fullName}", name="ed_frontend_blog_by_pilotname")
     * @Route("/blog/pilots/{fullName}", name="frontend_blog_by_pilotname")
     */
    public function singlePilotAction($fullName)
    {
        $Names = explode(" ", $fullName);
        $firstName = $Names[0] ;
        $lastName = $Names[1];
        $pilot = $this->get('app_repository_pilot')->findByName($firstName, $lastName);

        if(!($pilot))
        {
            throw new NotFoundHttpException("Pilot not found.");
        }
        return $this->render("MsportsEventBundle:Frontend/Blog:singlePilot.html.twig",
            array(
                'pilot' => $pilot[0]
                ));
    }

}


