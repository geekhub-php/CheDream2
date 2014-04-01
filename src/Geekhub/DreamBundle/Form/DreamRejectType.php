<?php
/**
 * Created by PhpStorm.
 * File: DreamRejectType.php
 * User: Yuriy Tarnavskiy
 * Date: 26.03.14
 * Time: 16:54
 */

namespace Geekhub\DreamBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DreamRejectType extends AbstractType
{
    public function getName()
    {
        return 'rejectedDreamForm';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rejectedDescription', 'textarea', array(
                'label' => 'dream.reject.description',
                'attr' => array(
                    'class' => 'tinymce',
                    'rows' => 15,
                )
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Geekhub\DreamBundle\Entity\Dream'
            )
        );
    }
}
