<?php
// src/AppBundle/Admin/PostAdmin.php

namespace BlogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class ClassementAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('pilot', 'sonata_type_model', array(
                   'required' => 'true'
            ))
            ->add('points')
            ->add('tours')
            ->add('rank')
            ->add('champ')
            ->add('course')
            ->add('time', 'time', array(
                   'required' => false,
                   'with_seconds' => true,
                   'with_seconds' => true,
                   'placeholder' => array(
                        'hour' => 0, 'minute' => 0, 'second' => 0,
                    ),
                ))
            ->add('time1', 'time', array(
                   'required' => false,
                   'with_seconds' => 'true',
                   'placeholder' => array(
                        'hour' => 0, 'minute' => 0, 'second' => 0,
                    ),
                ))
            ->add('time2', 'time', array(
                   'required' => false,
                   'with_seconds' => 'true',
                   'placeholder' => array(
                        'hour' => 0, 'minute' => 0, 'second' => 0,
                    ),
                ))
       ;
    }

    

    public function prePersist($classement)
    {
        $nam = "Classement ";
        $nam = $nam.$classement->getPilot().' ';
        if ($classement->getCourse)
            $nam = $nam.$classement->getCourse();
        else
            $nam = $nam.$classement->getChamp();
        $classement->setName($nam);
    }

    public function preUpdate($classement)
    { 
        $nam = "Classement ";
        $nam = $nam.$classement->getPilot().' ';
        if ($classement->getCourse)
            $nam = $nam.$classement->getCourse();
        else
            $nam = $nam.$classement->getChamp();
        $classement->setName($nam);
    }
    

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
       $datagridMapper
            ->add('name')
            ->add('pilot')
       ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('pilot')
       ;
    }

    // Fields to be shown on show action
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
           
       ;
    }
}