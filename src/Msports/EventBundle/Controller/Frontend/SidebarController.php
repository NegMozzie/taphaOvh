<?php

namespace Msports\EventBundle\Controller\Frontend;

use Msports\BlogBundle\Entity\Category;
use ED\BlogBundle\Handler\Pagination;
use Msports\BlogBundle\Entity\Article;
use Msports\EventBundle\Entity\GrandPrix;
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
          return $this->render('Msports\EventBundle:Frontend/Blog:blog_sidebar.html.twig', array(
              'events' => $events
          ));
    }
}
