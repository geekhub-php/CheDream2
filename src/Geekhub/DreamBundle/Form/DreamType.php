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
        $transformer = new TagTransformer();
        $builder
            ->add('title', 'text', array('label' => 'назва '))
            ->add('description', 'textarea', array('label' => 'опис '))
            ->add('phone', 'text', array('label' => 'телефон' ))
            ->add($builder->create('tags', 'text', array(
                'attr' => array('class' => 'form-control'),
                'required'  => false,
            ))->addModelTransformer($transformer))
            ->add('expiredDate', 'date', array(
                'input'  => 'datetime',
                'widget' => 'single_text',
                'label' => 'expireData fff ',
            ))
            ->add('financialResources', 'collection', array(
                'type' => new FinancialType(),
//                'mapped' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference'  => false,
//                'label' => 'фінанси '
            ))
            ->add('equipmentResources', 'collection', array(
                'type' => new EquipmentType(),
//                'mapped' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference'  => false,
//                'label' => 'обладнання '
            ))
            ->add('workResources', 'collection', array(
                'type' => new WorkType(),
//                'mapped' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference'  => false,
//                'label' => 'робота '
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
