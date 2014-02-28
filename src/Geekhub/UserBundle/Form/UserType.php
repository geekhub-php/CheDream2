<?php

namespace Geekhub\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Geekhub\UserBundle\Form\ContactsType;

class UserType extends AbstractType
{
    public function getName()
    {
        return 'newUserForm';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', 'text', array('label' => 'Ім\'я '))
            ->add('lastName', 'text', array('label' => 'Прізвище '))
            ->add('birthday', 'birthday', array('label' => 'День народження '))
            ->add('about', 'textarea', array('label' => 'Про себе '))
            //->add('avatar', 'file', array('required'=>false))
            //->add('phone', 'text', array('label' => 'телефон ' ))
            //->add('skype', 'text', array('label' => 'Skype ' ))
            ->add('contacts', new ContactsType(), array(
                'data_class' =>'Geekhub\UserBundle\Entity\Contacts',
                ))
            ->add('email', 'email', array('label' => 'Email ' ))
            ->add('facebookId', 'text', array('label' => 'Facebook ' ))
            ->add('vkontakteId', 'text', array('label' => 'Vkontakte ' ));
            /*->add('avatar', 'entity', array(
                 'class' => 'Application\Sonata\MediaBundleEntity:Media',
                 'property' => 'id',
                 'expanded' => false,
                 'multiple' => false,
                 'empty_value' => true,
                 ))*/

    }

}
