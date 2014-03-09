<?php
/**
 * Created by PhpStorm.
 * User: Yuriy Tarnavskiy
 * Date: 09.03.14
 * Time: 14:36
 */

namespace Geekhub\DreamBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WorkContributeType extends AbstractType
{
    public function getName()
    {
        return 'workContributeForm';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('workArticle', 'entity', array(
                'class' => 'GeekhubDreamBundle:WorkResource',
                'property' => 'title',
                'label' => 'dream.work.title'))
            ->add('quantity', 'integer', array('label' => 'dream.work.quantity'))
            ->add('quantityDays', 'integer', array('label' => 'dream.work.days'))
            ->add('hiddenContributor', 'checkbox', array(
                'label'     => 'hide.contribute',
                'required'  => false, ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Geekhub\DreamBundle\Entity\WorkContribute'
            )
        );
    }
}
