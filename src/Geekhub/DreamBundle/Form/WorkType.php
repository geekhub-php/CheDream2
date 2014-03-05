<?php
/**
 * Created by PhpStorm.
 * File: WorkType.php
 * User: Yuriy Tarnavskiy
 * Date: 11.02.14
 * Time: 12:30
 */

namespace Geekhub\DreamBundle\Form;

//use Geekhub\DreamBundle\Entity\AbstractContributeResource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WorkType extends AbstractType
{
    public function getName()
    {
        return 'workForm';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array('label' => 'dream.resource.title'))
            ->add('quantity', 'text', array('label' => 'dream.work.quantity'))
            ->add('quantityDays', 'text', array('label' => 'dream.work.days'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Geekhub\DreamBundle\Entity\WorkResource'
            )
        );
    }
}
