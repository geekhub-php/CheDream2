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
        /** @var \GeekHub\DreamBundle\Entity\Dream $dream */
        $dream = $options['dream'];
        $mediaManager = $options['media-manager'];

        $transformerTag = new TagTransformer();
        $transformerPicture = new DreamPicturesTransformer($dream, $mediaManager);
        $transformerPoster = new DreamPosterTransformer($dream, $mediaManager);
        $transformerFile = new DreamFilesTransformer($dream, $mediaManager);
        $transformerVideo = new DreamVideoTransformer($dream, $mediaManager);

        $builder
            ->add('title', 'text', array('label' => 'dream.title'))
            ->add('description', 'textarea', array(
                'label' => 'dream.description',
                'attr' => array(
                    'class' => 'tinymce',
                    'rows' => 15,
                )
            ))
            ->add('implementedDescription', 'textarea', array(
                'label' => 'dream.implementing.description',
                'attr' => array(
                    'class' => 'tinymce',
                    'rows' => 15,
                )
            ))
            ->add('phone', 'text', array('label' => 'dream.phone'))
            ->add($builder->create('tags', 'text', array(
                'required'  => false,
                'label' => 'tags',
            ))->addModelTransformer($transformerTag))
            ->add('expiredDate', 'date', array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'label' => 'dream.expired_date',
            ))
            ->add('dreamFinancialResources', 'collection', array(
                'type' => new FinancialType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference'  => false,
            ))
            ->add('dreamEquipmentResources', 'collection', array(
                'type' => new EquipmentType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference'  => false,
            ))
            ->add('dreamWorkResources', 'collection', array(
                'type' => new WorkType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference'  => false,
            ))
            ->add($builder->create('dreamPictures', 'hidden')->addModelTransformer($transformerPicture))
            ->add($builder->create('dreamPoster', 'hidden')->addModelTransformer($transformerPoster))
            ->add($builder->create('dreamFiles', 'hidden')->addModelTransformer($transformerFile))
            ->add($builder->create('dreamVideos', 'hidden')->addModelTransformer($transformerVideo));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Geekhub\DreamBundle\Entity\Dream',
            'cascade_validation' => true,
            ))
            ->setRequired(array('dream', 'media-manager'))
            ->setAllowedTypes(array(
                'dream' => 'Geekhub\DreamBundle\Entity\Dream',
                'media-manager' => 'Sonata\MediaBundle\Entity\MediaManager'
            ));
    }
}
