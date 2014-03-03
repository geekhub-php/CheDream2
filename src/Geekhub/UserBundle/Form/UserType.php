<?php

namespace Geekhub\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Geekhub\UserBundle\Form\ContactsType;
use Geekhub\UserBundle\Form\FileMediaTransformer;

class UserType extends AbstractType
{
    public function getName()
    {
        return 'newUserForm';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new FileMediaTransformer($entityManager);
        $builder
            ->add('firstName', 'text', array('label' => 'Ім\'я '))
            ->add('lastName', 'text', array('label' => 'Прізвище '))
            ->add('birthday', 'birthday', array('label' => 'День народження '))
            ->add('about', 'textarea', array('label' => 'Про себе '))
            ->add('contacts', new ContactsType(), array(
                'data_class' =>'Geekhub\UserBundle\Entity\Contacts',
                ))
            ->add('email', 'email', array('label' => 'Email ' ))
            ->add('facebookId', 'text', array('label' => 'Facebook ' , 'required' => false))
            ->add('vkontakteId', 'text', array('label' => 'Vkontakte ', 'required' => false ))
            ->add( $builder->create('avatar', 'file', array(
                      'label' => 'Avatar ', 
                      'required' => false))
              ->addModelTransformer($transformer)
             );

    }

}
