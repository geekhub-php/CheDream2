<?php
/**
 * Created by PhpStorm.
 * File: DreamType.php
 * User: Yuriy Tarnavskiy
 * Date: 11.02.14
 * Time: 11:50
 */

namespace Geekhub\DreamBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DreamType extends AbstractType
{
    public function getName()
    {
        return 'newDreamForm';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array('label' => 'назва'))
            ->add('description', 'textarea', array('label' => 'опис'))
            ->add('phone', 'text', array('label' => 'телефон'))
            ->add('expiredDate', 'date', array(
                'input'  => 'datetime',
                'widget' => 'single_text',
                'label' => 'expireData fff',
            ))
            ->add('dreamResources', 'collection', array(
                'type' => new FinancialType(),
//                'allow_add' => true,
//                'allow_delete' => true,
//                'by_reference'  => false,
                'label' => 'фінанси'
            ))
            ->add('dreamResources', 'collection', array(
                'type' => new EquipmentType(),
//                'allow_add' => true,
//                'allow_delete' => true,
//                'by_reference'  => false,
                'label' => 'обладнання'
            ))
            ->add('dreamResources', 'collection', array(
                'type' => new WorkType(),
//                'allow_add' => true,
//                'allow_delete' => true,
//                'by_reference'  => false,
                'label' => 'робота'
            ));
//            ->add('tag', 'collection', array(
//                'type' => new TagType(),
//                'allow_add' => true,
//                'allow_delete' => true,
//                'by_reference'  => false,
//                'label' => 'tags'
//            ));
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