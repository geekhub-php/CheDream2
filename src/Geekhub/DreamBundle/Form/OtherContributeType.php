<?php
/**
 * Created by PhpStorm.
 * User: Yuriy Tarnavskiy
 * Date: 12.03.14
 * Time: 23:58
 */

namespace Geekhub\DreamBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OtherContributeType extends AbstractType
{
    public function getName()
    {
        return 'otherContributeForm';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'label' => 'dream.other.title'))
            ->add('hiddenContributor', 'checkbox', array(
                'label'     => 'hide.contribute',
                'disabled' => true,
                'required'  => false, ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Geekhub\DreamBundle\Entity\OtherContribute'
        ));
    }
}
