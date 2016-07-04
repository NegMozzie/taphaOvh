<?php
// src/AppBundle/Admin/PostAdmin.php

namespace Msports\EventBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Symfony\Component\Form\Extension\Core\Type\RangeType;


class ChronoAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('hours')
            ->add('minutes')
            ->add('secondes')
            ->add('tierces')
       ;
    }    

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
       $datagridMapper
            ->add('hours')
            ->add('minutes')
            ->add('secondes')
            ->add('tierces')
       ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('hours')
            ->add('minutes')
            ->add('secondes')
            ->add('tierces')
       ;
    }

    // Fields to be shown on show action
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('hours')
            ->add('minutes')
            ->add('secondes')
            ->add('tierces')
       ;
    }
}