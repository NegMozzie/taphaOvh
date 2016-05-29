<?php

namespace  BlogBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EventType extends AbstractType
{
    protected $dataClass;
    protected $userClass;
    protected $entityManager;
    protected $authorizationChecker;
    protected $taxonomyClass;
    protected $teamClass;

    function __construct($dataClass, $userClass, $taxonomyClass, $teamClass,  $entityManager, AuthorizationChecker $authorizationChecker)
    {
        $this->dataClass = $dataClass;
        $this->entityManager = $entityManager;
        $this->userClass = $userClass;
        $this->taxonomyClass = $taxonomyClass;
        $this->teamClass = $teamClass;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $object = $builder->getData();
        $builder
            ->add('name', 'text')
            ->add('startsAt', 'datetime', array(
                'data'    => new \DateTime('now'),
                'years'   => range(date('Y')-1, date('Y')+5),
                'minutes' => range(0, 59, 5)
            ))
            ->add('endsAt', 'datetime', array(
                'data'    => new \DateTime('now'),
                'years'   => range(date('Y')-1, date('Y')+5),
                'minutes' => range(0, 59, 5)
            ))
            ->add('categories', 'entity', array(
                'class' => $this->taxonomyClass,
                'required' => false,
                'expanded' => true,
                'multiple' => true,
                'attr' => array(
                    'class' => 'js-get-pretty-categories',
                    'placeholder' => 'Select category'
                )))
            ->add('teams', 'entity', array(
                'class' => $this->teamClass,
                'required' => false,
                'expanded' => true,
                'multiple' => true,
                'attr' => array(
                    'class' => 'js-get-pretty-categories',
                    'placeholder' => 'Select teams'
                )))
            ->add('parent', 'entity', array(
                'label' => 'Parent event:',
                'required' => false,
                'class' => $this->dataClass,
                'placeholder' => 'Select parent event',
                'query_builder' => function (EntityRepository $er) use ($object) {
                    if($object && $object->getId())
                    {
                        return $er->createQueryBuilder('c')
                            ->andWhere('c <> :object')
                            ->orderBy('c.id', 'DESC')
                            ->setParameter('object', $object);
                    }
                    else
                    {
                        return $er->createQueryBuilder('c')
                            ->orderBy('c.id', 'DESC')
                    }
                },
                'attr' => array(
                    'class' => 'form-control form-control--lg margin--b hide js-get-pretty-categories',
                    'data-empty-option' => 'Select parent event'
                )
            ))
            ->add('save', 'submit',
                        array(
                            'attr' => array('class' => 'btn btn-md btn-b-blue btn-wide--xl flright--responsive-mob margin--r')
            ))
        ;
    }
    
    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "event";
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass,
        ));
    }
}