<?php

namespace BlogBundle\Controller\Backend;

use Application\Sonata\MediaBundle\Entity\Media;
use Doctrine\Common\Collections\ArrayCollection;
use BlogBundle\Event\ArticleAdministrationEvent;
use BlogBundle\Event\MediaArrayEvent;
use BlogBundle\Event\TaxonomyArrayEvent;
use BlogBundle\Events\EDBlogEvents;
use BlogBundle\Forms\ArticleExcerptType;
use BlogBundle\Forms\ArticlePhotoType;
use BlogBundle\Handler\Pagination;
use BlogBundle\Entity\Article;
use BlogBundle\Entity\Repository\UserRepository;

use Nelmio\ApiDocBundle\Util\LegacyFormHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UserController extends DefaultController
{
    /**
     * @Route("/user/list", name="ed_blog_user_list")
     */
    public function listAction(Request $request)
    {
        $user = $this->getBlogAdministrator();
        $paginator = $this->get('blog.paginator');
        $orderBy = $request->get('orderby', null);
        $order = $request->get('order', null);

        $response = $paginator->paginate(
            $this->get('app_repository_user')->getSortableQuery($orderBy, $order, $this->container->getParameter('article_class')),
            'BlogBundle:Users:list',
            'BlogBundle:Global:pagination',
            array(
                'orderBy' => $orderBy,
                'order' => $order
            ),
            Pagination::MEDIUM,
            null,
            'BlogBundle:Global:paginationClassic.html.twig',
            array()
        );

        return $response;
    }

    /**
     * @Route("/user/add", name="ed_blog_user_add")
     */
    public function addAction(Request $request)
    {
        $admin = $this->getBlogAdministrator();

        $form = $this->createForm('edblog_user');

        if($request->isMethod('post'))
        {
            $form->handleRequest($request);
            $user = $this->get('user_generator')->getObject();

            if($form->isValid())
            {
                $this->get('blog.handler.blog_user_handler')->revokeBlogRoles($user);
                $user
                    ->setBlogDisplayName( $form['blogDisplayName']->getData())
                    ->setUsername($form['username']->getData())
                    ->setPlainPassword($form['plainPassword']->getData())
                    ->addRole('ROLE_BLOG_USER')
                    ->setEmail( $form['email']->getData())
                    ->setEnabled((Boolean) true)
                    ->setSuperAdmin((Boolean) false)
                    ->addRole( $form['role']->getData());

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'User updated successfully.');
            }
        }

        return $this->render('@Blog/Users/add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/user/search", name="ed_blog_user_search")
     */
    public function searchAction(Request $request)
    {
        $user = $this->getBlogAdministrator();

        $results = $this
            ->getDoctrine()
            ->getRepository( get_class($user))
            ->createQueryBuilder('u')
            ->select('u.email AS value')
            ->where('u.email LIKE :term')
            ->andWhere('u.enabled = 1')
            ->andWhere('u.roles NOT LIKE :roleBlogUser')
            ->setParameter('term', "%" . $request->get('query') . "%")
            ->setParameter('roleBlogUser', '%ROLE_BLOG_USER%')
            ->getQuery()
            ->getResult();

        return new JsonResponse(array( "suggestions" => $results));
    }

    /**
     * @Route("/user/edit/{username}", name="ed_blog_user_edit")
     * @ParamConverter("user", class="BlogBundle\Entity\User", converter="abstract_converter")
     */
    public function editAction(Request $request, $user)
    {
        $admin = $this->getBlogAdministrator();

        $form = $this->createForm('edblog_user', array(
            'role' => $this->getDefaultBlogRole($user),
            'blogDisplayName' => $user->getBlogDisplayName()
        ));

        if($request->isMethod('post'))
        {
            $form->handleRequest($request);

            if($form->isValid())
            {
                $this->get('blog.handler.blog_user_handler')->revokeBlogRoles($user);
                $user
                    ->setBlogDisplayName( $form['blogDisplayName']->getData() )
                    ->addRole('ROLE_BLOG_USER')
                    ->addRole( $form['role']->getData() );

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'User updated successfully.');
            }
        }

        return $this->render('@Blog/Users/edit.html.twig', array(
            'form' => $form->createView(),
            'user' => $user
        ));
    }

    /**
     * @Route("/user/{username}/revoke", name="ed_blog_user_revoke")
     * @ParamConverter("user", class="BlogBundle\Entity\BlogUser", converter="abstract_converter")
     */
    public function revokeAction($user)
    {
        $admin = $this->getBlogAdministrator();
        $em = $this->getDoctrine()->getManager();

        $this->get('blog.handler.blog_user_handler')->revokeBlogRoles($user);

        $em->persist($user);
        $em->flush();
        $this->get('session')->getFlashBag()->add('success', 'User revoked successfully.');

        return $this->redirectToRoute('ed_blog_user_list');
    }

}


