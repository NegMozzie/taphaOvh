<?php

namespace Msports\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MsportsUserBundle:Default:index.html.twig');
    }
}
