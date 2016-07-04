<?php
/**
 * Creat by Eton Digital.
 * User: Vladimir Mladenovic (vladimir.mladenovic@etondigital.com)
 * Date: 12.5.15.
 * Time: 10.45
 */

namespace Msports\EventBundle\Controller\Frontend;

use ED\BlogBundle\Handler\Pagination;
use Msports\EventBundle\Entity\team;
use Msports\BlogBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TeamController extends Controller
{
    /**
     * @Route("/category/{categorySlug}/teames", name="ed_blog_teams")
     * @Route("/category/{categorySlug}/teames/", name="ed_blog_frontend_teams")
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
            $this->get('app_repository_team')->findByTaxonomy($categorySlug,$taxonomyType),
            'MsportsEventBundle:Frontend/Blog:teams',
            'EDBlogBundle:Frontend/Global:pagination',
            array("criteria" => $criteria),
            50,
            null,
            $paginationTemplate = 'EDBlogBundle:Frontend/Global:pagination.html.twig',
            array(),
            null
        );

        return $response;
    }


    /**
     * @Route("/category/teams/{teamName}", name="ed_frontend_blog_by_teamname")
     * @Route("/category/teams/{teamName}", name="frontend_blog_by_teamname")
     */
    public function singleteamAction($teamName)
    {
        $team = $this->get('app_repository_team')->findByName($teamName);

        if(!($team))
        {
            throw new NotFoundHttpException("team not found.");
        }
        return $this->render("MsportsEventBundle:Frontend/Blog:singleteam.html.twig",
            array(
                'team' => $team
                ));
    }

}


