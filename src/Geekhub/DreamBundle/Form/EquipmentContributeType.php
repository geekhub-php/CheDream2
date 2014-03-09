<?php
/**
 * Created by PhpStorm.
 * User: Yuriy Tarnavskiy
 * Date: 09.03.14
 * Time: 14:31
 */

namespace Geekhub\DreamBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EquipmentContributeType extends AbstractType
{
    public function getName()
    {
        return 'equipmentContributeForm';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('equipmentArticle', 'entity', array(
                'class' => 'GeekhubDreamBundle:EquipmentResource',
                'property' => 'title',))
            ->add('quantity', 'integer', array('label' => 'dream.equipment.quantity'))
            ->add('hiddenContributor', 'checkbox', array(
                'label'     => 'Hide contribute?',
                'required'  => false, ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Geekhub\DreamBundle\Entity\EquipmentContribute'
            )
        );
    }
}
