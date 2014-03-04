<?php
/**
 * Created by PhpStorm.
 * File: EquipmentType.php
 * User: Yuriy Tarnavskiy
 * Date: 11.02.14
 * Time: 12:29
 */

namespace Geekhub\DreamBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Geekhub\DreamBundle\Entity\AbstractContributeResource;

class EquipmentType extends AbstractType
{
    public function getName()
    {
        return 'equipmentForm';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array('label' => 'назва'))
            ->add('quantity', 'text', array('label' => 'колличество'))
            ->add('quantityType', 'choice', array(
                'choices' => array(
                    AbstractContributeResource::PIECE => 'шт.',
                    AbstractContributeResource::KG => 'кг',
                    AbstractContributeResource::TON => 'тон'
                ),
//                'preferred_choices' => array(AbstractContributeResource::PIECE),
                'label' => 'Тип'
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Geekhub\DreamBundle\Entity\EquipmentResource'
            )
        );
    }
}
