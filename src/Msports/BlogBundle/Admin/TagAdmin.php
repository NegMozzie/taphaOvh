<?php
// src/AppBundle/Admin/PostAdmin.php

namespace Msports\BlogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Msport\BlogBundle\Entity\Category;

class TagAdmin extends Admin
{

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('term', 'sonata_type_model', array(
                'required' => true
            ))
            ->add('description', 'text')
            ->add('parent', 'sonata_type_model')
            ->add('type', 'hidden', array(
                'data' => Categoryy::TYPE_TAG
            ))
       ;
    }
    

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
       $datagridMapper
            ->add('term')
            ->add('type')
       ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('term')
            ->add('type')
       ;
    }

    // Fields to be shown on show action
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
           ->add('term')
       ;
    }
}