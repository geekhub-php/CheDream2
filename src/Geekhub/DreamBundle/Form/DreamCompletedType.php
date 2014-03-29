<?php
/**
 * Created by PhpStorm.
 * File: DreamCompletedType.php
 * User: Yuriy Tarnavskiy
 * Date: 28.03.14
 * Time: 15:11
 */

namespace Geekhub\DreamBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DreamCompletedType extends AbstractType
{
    public function getName()
    {
        return 'completingDreamForm';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var \GeekHub\DreamBundle\Entity\Dream $dream */
        $dream = $options['dream'];
        $mediaManager = $options['media-manager'];
        $transformerPicture = new DreamPicturesTransformer($dream, $mediaManager, $completed = true);
        $builder
            ->add('completedDescription', 'textarea', array(
                'label' => 'dream.completed.description',
                'attr' => array(
                    'class' => 'tinymce',
                    'rows' => 15,
                )
            ))
            ->add($builder->create('dreamPictures', 'hidden')->addModelTransformer($transformerPicture));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Geekhub\DreamBundle\Entity\Dream',
        ))
            ->setRequired(array('dream', 'media-manager'))
            ->setAllowedTypes(array(
                'dream' => 'Geekhub\DreamBundle\Entity\Dream',
                'media-manager' => 'Sonata\MediaBundle\Entity\MediaManager'
            ));
    }

}