<?php

namespace BlogBundle\Controller\Backend;

use BlogBundle\Handler\Pagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends DefaultController
{
    /**
     * @Route("/event/create", name="ed_blog_event_create")
     */
    public function createAction(Request $request)
    {
        $user = $this->getBlogUser();
        $categoryRepository = $this->get('app_repository_taxonomy');
        $teamRepository = $this->get('app_repository_team');
        $draft = $this->get('event_generator')->getObject();
        $form = $this->createForm('event', $draft);

        if ($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);

            if($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $draft = $form->getData();
                $draft
                    ->setModifiedAt(new \DateTime())
                    ->setCreatedAt(new \DateTime());

                $em->persist($draft);
                $em->flush();

                //must be after flush
                $dispacher = $this->get('event_dispatcher');
                $taxonomies = array_merge( $draft->getCategories()->toArray());
                $teams = array_merge( $draft->getTeams()->toArray());

                $dispacher->dispatch(EDBlogEvents::ED_BLOG_ARTICLE_CREATED, new TaxonomyArrayEvent( $taxonomies ));

            }
        }
        return $this->render("BlogBundle:Team:create.html.twig",
            array(
                'form' => $form->createView(),
                'categories' => $categoryRepository->getAllCategories(),
                'teams' => $teamRepository->getAllTeams(),
            ));
    }
}