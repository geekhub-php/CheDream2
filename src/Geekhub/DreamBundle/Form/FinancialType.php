<?php
/**
 * Created by PhpStorm.
 * File: FinancialType.php
 * User: Yuriy Tarnavskiy
 * Date: 11.02.14
 * Time: 12:29
 */

namespace Geekhub\DreamBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FinancialType extends AbstractType
{
    public function getName()
    {
        return 'financialForm';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array('label' => 'dream.financial.title'))
            ->add('quantity', 'money', array('label' => 'dream.financial.quantity', 'currency' => 'UAH', 'grouping' => true));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Geekhub\DreamBundle\Entity\FinancialResource',
                'cascade_validation' => true
            )
        );
    }
}
