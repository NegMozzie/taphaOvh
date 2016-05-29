<?php
// src/AppBundle/Admin/PostAdmin.php

namespace BlogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use BlogBundle\Entity\Taxonomy;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class CategoryAdmin extends Admin
{
    protected $dataClass;
     protected $baseRouteName = 'admin23';

    protected $baseRoutePattern = 'tag12';


    protected function configureFormFields(FormMapper $formMapper)
    {
        $this->dataClass = 'BlogBundle\Entity\Taxonomy';
        $formMapper
            ->add('term', 'sonata_type_model', array(
                'required' => true
            ))
            ->add('excerptPhoto', 'sonata_type_model', array(
            'class' => 'Application\Sonata\MediaBundle\Entity\Media',
            'property' => 'name'
            ))
            ->add('description', 'text')
            ->add('parent', 'sonata_type_model',  array(
                'required' => false
            ))
            ->add('type', 'hidden', array(
                'data' => Taxonomy::TYPE_CATEGORY
            ))
       ;
    }

    public function prePersist($category)
    {
        
    }
    

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
       $datagridMapper
            ->add('term')
            ->add('parent')
            ->add('type')
       ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('term.title')
            ->add('parent')
            ->add('type')
       ;
    }

    // Fields to be shown on show action
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('term')
            ->add('parent')
            ->add('type')
       ;
    }
}