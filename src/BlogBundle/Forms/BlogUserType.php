<?php
/**
 * Created by Eton Digital.
 * User: Vladimir Mladenovic (vladimir.mladenovic@etondigital.com)
 * Date: 26.6.15.
 * Time: 15.26
 */

namespace  BlogBundle\Forms;


use  BlogBundle\Handler\BlogUserHandler;

use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use BlogBundle\Transformers\UserToEmailTransformer;

class BlogUserType extends AbstractType
{
    protected $blogUserHandler;

    function __construct(BlogUserHandler $blogUserHandler)
    {
        $this->blogUserHandler = $blogUserHandler;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

           $builder
               ->add('blogDisplayName', 'text', array(
                   'label' => 'Display Name:',
                   'data' => isset($options['data']['blogDisplayName']) ? $options['data']['blogDisplayName'] : null,
                   'attr' => array(
                       'class' => 'form-control form-control--lg margin--b',
                       'placeholder' => 'Enter blog user name'
                   )
               ))
               ->add('role', 'choice', array(
                'label' => 'Roles?',
                'expanded' => true,
                'choices' => $this->blogUserHandler->getBlogRolesArray(),
                'data' => isset($options['data']['role']) ? $options['data']['role'] : null
                ))
               ->add('email', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\EmailType'), array(
                  'label' => 'Email:', 
                  'translation_domain' => 'FOSUserBundle',
                  'attr' => array(
                       'class' => 'form-control form-control--lg margin--b',
                       'placeholder' => 'Enter user email'
                    )
                  ))
               ->add('username', null, array(
                  'label' => 'Username:',
                  'translation_domain' => 'FOSUserBundle',
                  'attr' => array(
                       'class' => 'form-control form-control--lg margin--b',
                       'placeholder' => 'Enter username'
                    )
                  ))
               ->add('plainPassword', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\RepeatedType'), array(
                'type' => LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\PasswordType'),
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'Password:', 'attr' => array(
                       'class' => 'form-control form-control--lg margin--b',
                    )),
                'second_options' => array('label' => 'Password Cconfirmation', 'attr' => array(
                       'class' => 'form-control form-control--lg margin--b',
                    )),
                'invalid_message' => 'fos_user.password.mismatch',
                'attr' => array(
                       'class' => 'form-control form-control--lg margin--b',
                    )
            ))
               ->add('Save', 'submit', array(
                'attr' => array(
                    'class' => 'btn btn-md btn-primary btn-wide--xl flright--responsive-mob margin--b'
                ))
            );
    }

    public function getName()
    {
        return "edblog_user";
    }
}