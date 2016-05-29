<?php
// src/AppBundle/Admin/PostAdmin.php

namespace BlogBundle\Admin;

use BlogBundle\Handler\UserHandler;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class PilotAdmin extends Admin
{

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('firstName')    
            ->add('lastName')
            ->add('excerptPhoto', 'sonata_type_model', array(
              'class' => 'Application\Sonata\MediaBundle\Entity\Media',
              'property' => 'name'
            ))
            ->add('countryPhoto', 'sonata_type_model', array(
            'class' => 'Application\Sonata\MediaBundle\Entity\Media',
            'property' => 'name'
            ))
            ->add('biography')
            ->add('team')
        ;
    }

    public function prePersist($pilot)
    {
        $pilot->setUserName($pilot->getFirstName(). ' ' .$pilot->getLastName());
        $pilot->setEmail($pilot->getFirstName(). ' ' .$pilot->getLastName()."pilot.com");
        $pilot->setPassword("");
    }
    
    public function preUpdate($pilot)
    {
        if ($pilot->getUserName()  == "");
          $pilot->setUserName($pilot->getFirstName(). ' ' .$pilot->getLastName());
    }
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
       $datagridMapper
            ->add('firstName')
            ->add('lastName')
            ->add('biography')
       ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('firstName')
            ->add('lastName')
       ;
    }

    // Fields to be shown on show action
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('firstName')
            ->add('lastName')
       ;
    }
}