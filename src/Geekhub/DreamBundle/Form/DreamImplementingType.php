<?php
/**
 * Created by PhpStorm.
 * File: DreamImplementingType.php
 * User: Yuriy Tarnavskiy
 * Date: 27.03.14
 * Time: 12:07
 */

namespace Geekhub\DreamBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DreamImplementingType extends AbstractType
{
    public function getName()
    {
        return 'implementingDreamForm';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('implementedDescription', 'textarea', array(
                'label' => 'dream.implementing.description',
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
