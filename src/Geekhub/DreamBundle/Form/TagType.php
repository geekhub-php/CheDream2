<?php
/**
 * Created by PhpStorm.
 * File: TagType.php
 * User: Yuriy Tarnavskiy
 * Date: 11.02.14
 * Time: 14:33
 */

namespace Geekhub\DreamBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TagType extends AbstractType
{
    public function getName()
    {
        return 'tagForm';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => 'назва tag'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Geekhub\TagBundle\Entity\Tag'
            )
        );
    }
}
