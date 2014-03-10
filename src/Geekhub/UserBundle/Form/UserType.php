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
            ->add('about', 'textarea', array('label' => 'user.about_myself', 'required' => false))
            ->add('contacts', new ContactsType(), array(
                'data_class' =>'Geekhub\UserBundle\Entity\Contacts',
                ))
            ->add('email', 'email', array('label' => 'user.email' ))
            ->add('facebookId', 'text', array('label' => 'user.facebook' , 'required' => false))
            ->add('vkontakteId', 'text', array('label' => 'user.vkontakte', 'required' => false ))
            ->add('odnoklassnikiId', 'text', array('label' => 'user.odnoklassniki', 'required' => false ))
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
