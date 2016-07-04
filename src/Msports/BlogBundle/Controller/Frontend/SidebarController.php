<?php

namespace Msports\BlogBundle\Controller\Frontend;

use ED\BlogBundle\Model\Entity\Taxonomy;
use ED\BlogBundle\Controller\Frontend\SidebarController as BaseController;

class SidebarController extends BaseController
{
    public function blogListAction()
    {
          $response = parent::blogListAction();
          $paginator = $this->get('ed_blog.paginator');
          $events = $this->get('app_repository_grandprix')->findWeekEvents();
          return $this->render('MsportsEventBundle:Frontend/Blog:blog_sidebar.html.twig', array(
              'events' => $events
          ));
    }
}
