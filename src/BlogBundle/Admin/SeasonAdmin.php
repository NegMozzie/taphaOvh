<?php
// src/AppBundle/Admin/PostAdmin.php

namespace BlogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use BlogBundle\Entity\Season;


class SeasonAdmin extends Admin
{

    protected function configureFormFields(FormMapper $formMapper)
    {
        $years = range('1980', date('Y') + 1);
        $years = array_combine($years, $years);
        $formMapper
            ->add('startYear', 'choice', array(
                    'choices' => $years,
                    'choices_as_values' => true,
            ))
            ->add('endYear', 'choice', array(
                'choices' => $years
            ))
            ->add('status', ChoiceType::class, array(
                    'label' => 'Status:',
                    'choices' => array(
                        "En cours" => Season::STATUS_PRESENT,
                        "Finie" => Season::STATUS_PAST,
                        "A venir" => Season::STATUS_FUTURE
                    ),
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control form-control--lg margin--halfb",
                    ),
                    'choices_as_values' => true,
            ))
       ;
    }

    public function prePersist($season)
    {
        $season->setName('Saison '.$season->getStartYear().'/'.$season->getEndYear());
    }
    

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
       $datagridMapper
            ->add('startYear')
            ->add('endYear')
            ->add('status')
       ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('startYear')
            ->add('endYear')
            ->add('status')
       ;
    }

    // Fields to be shown on show action
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('startYear')
            ->add('endYear')
            ->add('status')
       ;
    }
}