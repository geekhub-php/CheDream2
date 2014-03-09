<?php
/**
 * Created by PhpStorm.
 * User: Yuriy Tarnavskiy
 * Date: 09.03.14
 * Time: 14:24
 */

namespace Geekhub\DreamBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FinancialContributeType extends AbstractType
{
    public function getName()
    {
        return 'financialContributeForm';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('financialArticle', 'entity', array(
                'class' => 'GeekhubDreamBundle:FinancialResource',
                'property' => 'title',))
            ->add('quantity', 'money', array('label' => 'dream.financial.quantity', 'currency' => 'UAH', 'grouping' => true))
            ->add('hiddenContributor', 'checkbox', array(
                'label'     => 'Hide contribute?',
                'required'  => false, ))
            ->add('financialSubmit', 'submit', array('label' => 'fin Ok'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Geekhub\DreamBundle\Entity\FinancialContribute'
            )
        );
    }
}
