<?php

namespace BlogBundle\Controller\Frontend;

use BlogBundle\Entity\Taxonomy;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NavbarController extends Controller
{
    public function categoryListAction()
    {
          $categories = $this->get('app_repository_taxonomy')
              ->createQueryBuilder('c')
              ->where('c.parent IS NULL')
              ->andWhere('c.type = :categoryType')
              ->setParameter('categoryType', Taxonomy::TYPE_CATEGORY)
              ->getQuery()
              ->getResult();

          $archiveYearsMonths = $this->get('app_repository_article')->getYearsMonthsOfArticles();

          return $this->render('BlogBundle:Frontend/Blog:blog_navbar.html.twig', array(
              'categories' => $categories,
              'archive' => $archiveYearsMonths
          ));
    }

    public function footerAction()
    {
          $categorySlug = "formule-1";
          $taxonomy = $this->get('app_repository_taxonomy')->findBySlug($categorySlug);
          $taxonomyType = Taxonomy::TYPE_CATEGORY;
          if(!($taxonomy && $taxonomy->getType()==$taxonomyType))
          {
              throw new NotFoundHttpException("Category not found.");
          }
          return $this->render('BlogBundle:Frontend/Blog:footer.html.twig', array(
            'teams' => $this->get('app_repository_team')->findByTaxonomy($categorySlug,$taxonomyType),
          ));
    }
}
