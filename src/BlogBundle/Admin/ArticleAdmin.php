<?php
// src/AppBundle/Admin/PostAdmin.php

namespace BlogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Doctrine\ORM\EntityRepository;
use BlogBundle\Entity\Article;
use BlogBundle\Entity\Taxonomy;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ArticleAdmin extends Admin
{
    protected $dataClass;
    protected $userClass;
    protected $entityManager;
    protected $authorizationChecker;
    protected $categoryClass = 'BlogBundle\Entity\Taxonomy';

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', 'text',
                array(
                    'required' => true,
                    'label' => 'Title:',
                    'attr' => array(
                        'class' => 'form-control form-control--lg margin--b',
                        'placeholder' => 'Enter title of the article'
                    )
                ))
            ->add('excerpt', 'textarea',
                array(
                    'required' => false,
                    'label' => 'Excerpt text:',
                    'attr' => array(
                        'class' => 'form-control form-control--lg margin--halfb',
                        'rows'  => 2,
                        'placeholder' => 'Enter excerpt text'
                    )
                ))
            ->add('excerptPhoto', 'sonata_type_model', array(
            'class' => 'Application\Sonata\MediaBundle\Entity\Media',
            'property' => 'name'
            ))
            ->add('content')
            ->add('author', 'sonata_type_model')
            ->add('categories', 'entity', array(
                    'label' => 'Categories:',
                    'multiple' => true,
                    'required' => true,
                    'class' => $this->categoryClass,
                    'placeholder' => 'Selectionnez les categories',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('a')
                            ->innerJoin('a.term', 'p')
                            ->where('a.type like :type')
                            ->orderBy('p.title', 'ASC')
                            ->setParameter('type', Taxonomy::TYPE_CATEGORY);
                    },
                    'attr' => array(
                        'class' => 'form-control form-control--lg color-placeholder',
                    )
                ))
            ->add('tags', 'entity', array(
                    'label' => 'Tags:',
                    'required' => false,
                    'multiple' => true,
                    'class' => $this->categoryClass,
                    'placeholder' => 'Selectionnez les tags',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('a')
                            ->innerJoin('a.term', 'p')
                            ->where('a.type like :type')
                            ->orderBy('p.title', 'ASC')
                            ->setParameter('type', Taxonomy::TYPE_TAG);
                    },
                    'attr' => array(
                        'class' => 'form-control form-control--lg color-placeholder',
                    )
                ))
            ->add('metaData')
            ->add('status', ChoiceType::class, array(
                    'label' => 'Status:',
                    'choices' => array(
                        "Published" => Article::STATUS_PUBLISHED,
                        "Draft" => Article::STATUS_DRAFTED
                    ),
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control form-control--lg margin--halfb",
                    ),
                    'choices_as_values' => true,
            ))       
       ;
    }
    
    public function prePersist($article)
    {
        $article->setCreatedAt(new \DateTime());
        $article->setModifiedAt(new \DateTime());
        $article->setSlug('drafted-' . $article->getId());
        if ($article->getStatus() == Article::STATUS_PUBLISHED)
            $article->setPublishedAt(new \DateTime());
    }

    public function preUpdate($article)
    {
        if ($article->getStatus() == Article::STATUS_PUBLISHED && !($article->getPublishedAt()))
            $article->setPublishedAt(new \DateTime(date("Y-m-d H:i:s")));
    }


    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
       $datagridMapper
            ->add('title')
            ->add('excerptPhoto')
            ->add('excerpt')
       ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('excerptPhoto')
            ->add('excerpt')
       ;
    }

    // Fields to be shown on show action
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('title')
            ->add('excerptPhoto')
            ->add('excerpt')
       ;
    }
}