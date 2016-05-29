<?php
// src/AppBundle/Admin/PostAdmin.php

namespace BlogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use BlogBundle\Entity\Taxonomy;
use BlogBundle\Entity\Comment;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class CommentAdmin extends Admin
{

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('comment')
            ->add('author')
            ->add('tour')
       ;
    }

    public function prePersist($comment)
    {
        $comment->setCreatedAt(new \DateTime());
        $comment->setModifiedAt(new \DateTime());
        $comment->setStatus( Comment::STATUS_ACTIVE );
    }
    

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
       $datagridMapper
            ->add('comment')
            ->add('author')
       ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('comment')
            ->add('author')
       ;
    }

    // Fields to be shown on show action
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
           ->add('comment')
            ->add('author')
       ;
    }
}