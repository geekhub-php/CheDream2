<?php

namespace Geekhub\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Geekhub\UserBundle\Form\ContactsType;
use Geekhub\UserBundle\Form\FileMediaTransformer;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonata\MediaBundle\Entity\MediaManager;
use Geekhub\UserBundle\Entity\User;

class UserType extends AbstractType
{
    public function getName()
    {
        return 'newUserForm';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new FileMediaTransformer($options['user'], $options['media-manager']);
        $builder
            ->add('firstName', 'text', array('label' => 'Ім\'я '))
            ->add('lastName', 'text', array('label' => 'Прізвище '))
            ->add('birthday', 'birthday', array('label' => 'user.birthday', 'required' => false))
            ->add('about', 'textarea', array('label' => 'user.about_myself', 'required' => false, 
                   'attr' => array(
                       'class'=>'tinymce',
                       'rows' => 12,
                    )))
            ->add('email', 'email', array('label' => 'user.email'))
            ->add('phone', 'text', array('label' => 'user.phone', 'required' => false))
            ->add('skype', 'text', array('label' => 'user.skype', 'required' => false))
            ->add('facebookUrl', 'url', array('label' => 'user.facebook' , 'required' => false))
            ->add('vkontakteUrl', 'url', array('label' => 'user.vkontakte', 'required' => false ))
            ->add('odnoklassnikiUrl', 'url', array('label' => 'user.odnoklassniki', 'required' => false ))
            ->add( $builder->create('avatar', 'hidden')->addModelTransformer($transformer)
             );

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Geekhub\UserBundle\Entity\User',
            'cascade_validation' => true,
            ))
            ->setRequired(array('user', 'media-manager'))
            ->setAllowedTypes(array(
                'user' => 'Geekhub\UserBundle\Entity\User',
                'media-manager' => 'Sonata\MediaBundle\Entity\MediaManager',
            ));
    }

}
