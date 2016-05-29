<?php

namespace BlogBundle\Controller\Frontend;

use BlogBundle\Entity\Taxonomy;
use BlogBundle\Handler\Pagination;
use BlogBundle\Entity\Article;
use BlogBundle\Entity\GrandPrix;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SidebarController extends Controller
{
    public function blogListAction()
    {
          $paginator = $this->get('blog.paginator');
          $events = $this->get('app_repository_grandprix')->findWeekEvents();
          return $this->render('BlogBundle:Frontend/Blog:blog_sidebar.html.twig', array(
              'events' => $events
          ));
    }
}
