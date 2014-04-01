<?php

namespace Geekhub\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Geekhub\UserBundle\Form\ContactsType;
use Geekhub\UserBundle\Form\FileMediaTransformer;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonata\MediaBundle\Entity\MediaManager;
use Geekhub\UserBundle\Entity\User;

class RegistrationUserType extends AbstractType
{
    public function getName()
    {
        return 'geekhub_user_registration';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$transformer = new FileMediaTransformer($options['data']['user'], $options['data']['media-manager']);
        $builder
            ->add('username', 'text', array('label' => 'username '))
            ->add('plainPassword', 'text', array('label' => 'password '))
            ->add('firstName', 'text', array('label' => 'Ім\'я '))
            ->add('lastName', 'text', array('label' => 'Прізвище '))
            ->add('birthday', 'birthday', array('label' => 'user.birthday', 'required' => false))
            ->add('email', 'email', array('label' => 'user.email'))
            ->add('phone', 'text', array('label' => 'user.phone'));
            //->add( $builder->create('avatar', 'hidden')//->addModelTransformer($transformer)
            // );

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Geekhub\UserBundle\Entity\User',
            'cascade_validation' => true,
            ));
    }

}
