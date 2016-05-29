<?php
/**
 * Created by Eton Digital.
 * User: Vladimir Mladenovic (vladimir.mladenovic@etondigital.com)
 * Date: 8.5.15.
 * Time: 13.32
 */

namespace BlogBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomepageController extends DefaultController
{
    /**
     * @Route("/", name="ed_admin_index")
     */
    public function indexAction()
    {
        $user = $this->getBlogUser();

        return $this->render("BlogBundle:Homepage:index.html.twig", array());
    }

}