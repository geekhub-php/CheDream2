<?php

namespace Geekhub\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactsType extends AbstractType
{
    public function getName()
    {
        return 'newContactsForm';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phone', 'text', array('label' => 'user.phone', 'required' => false ))
            ->add('skype', 'text', array('label' => 'user.skype', 'required' => false ));
    }
}
