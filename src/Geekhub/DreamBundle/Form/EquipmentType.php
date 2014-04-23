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
use Geekhub\DreamBundle\Entity\EquipmentResource;

class EquipmentType extends AbstractType
{
    public function getName()
    {
        return 'equipmentForm';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', ['label' => false, 'attr' => ['class' => 'resource-field width-1']])
            ->add('quantityType', 'choice', [
                'choices' => array(EquipmentResource::getReadableQuantityTypes()),
                'label'   => false,
                'attr' => ['class' => 'resource-field width-2 input-view']
            ])
            ->add('quantity', 'integer', ['label' => false, 'attr' => ['class' => 'resource-field width-3']]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Geekhub\DreamBundle\Entity\EquipmentResource',
                'cascade_validation' => true
            )
        );
    }
}
